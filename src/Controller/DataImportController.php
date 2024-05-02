<?php

namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Entity\Piece;
use App\Entity\TypePiece;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use RandomLib\Factory as RandomLibFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class DataImportController extends AbstractController
{
    #[Route('/data/import', name: 'app_data_import')]
    public function index(EntityManagerInterface $entity, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usersJson = file_get_contents('./users.json');
        $users = json_decode($usersJson, true);

        $randomLibFactory = new RandomLibFactory();
        $generator = $randomLibFactory->getMediumStrengthGenerator();

        $createdUsers = [];

        foreach ($users as $userData) {
            $existingUser = $entity->getRepository(User::class)->findOneBy(['email' => $userData['email']]);

            if (!$existingUser) {
                $user = new User();
                $user->setNom($userData['nom']);
                $user->setEmail($userData['email']);

                $user->setTel($userData['tel']);
                $user->setCarteAgentImmo($userData['carteAgentImmo']);

                $randomPassword = $generator->generateString(10);
                $hashedPassword = $passwordHasher->hashPassword($user, $randomPassword);
                $user->setPassword($hashedPassword);

                $entity->persist($user);
                $createdUsers[$user->getNom()] = $randomPassword;
            }
        }



        $entity->flush();


        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
            'created_users' => $createdUsers,
        ]);
    }
    #[Route('/data/importbienimmo', name: 'app_data_importbienimmo')]
    public function importbienimmo(EntityManagerInterface $entity): Response
    {
        $biensImmoJson = file_get_contents('./biensimmo.json');
        $biensImmo = json_decode($biensImmoJson, true);

        foreach ($biensImmo as $bienImmoData) {
            $bienImmo = new BienImmobilier();
            $bienImmo->setRue($bienImmoData['rue']);
            $bienImmo->setCodePostal($bienImmoData['code_postal']);
            $bienImmo->setVille($bienImmoData['ville']);


            $user = $entity->getRepository(User::class)->find($bienImmoData['user_id']);


            $bienImmo->setUser($user);


            $entity->persist($bienImmo);
        }

        $entity->flush();

        return $this->redirectToRoute('app_data_import');
    }

    #[Route('/data/importpieces', name: 'app_data_importpieces')]
    public function importpieces(EntityManagerInterface $entity): Response
    {
        $piecesJson = file_get_contents('./pieces.json');
        $pieces = json_decode($piecesJson, true);

        foreach ($pieces as $pieceData) {
            $pieces = new Piece();
            $pieces->setSurface($pieceData['surface']);

            $bienImmo = $entity->getRepository(BienImmobilier::class)->find($pieceData['bien_immobilier_id']);
            $typePiece = $entity->getRepository(TypePiece::class)->find($pieceData['type_piece_id']);

            $pieces->setBienImmobilier($bienImmo);
            $pieces->setTypePiece($typePiece);

            $entity->persist($pieces);
        }

        $entity->flush();

        return $this->redirectToRoute('app_data_import');
    }
}
