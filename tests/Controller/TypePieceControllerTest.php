<?php

namespace App\Test\Controller;

use App\Entity\TypePiece;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TypePieceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/type/piece/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(TypePiece::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('TypePiece index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'type_piece[libelle]' => 'Testing',
            'type_piece[surfaceHabitable]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new TypePiece();
        $fixture->setLibelle('My Title');
        $fixture->setSurfaceHabitable('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('TypePiece');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new TypePiece();
        $fixture->setLibelle('Value');
        $fixture->setSurfaceHabitable('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'type_piece[libelle]' => 'Something New',
            'type_piece[surfaceHabitable]' => 'Something New',
        ]);

        self::assertResponseRedirects('/type/piece/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getLibelle());
        self::assertSame('Something New', $fixture[0]->getSurfaceHabitable());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new TypePiece();
        $fixture->setLibelle('Value');
        $fixture->setSurfaceHabitable('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/type/piece/');
        self::assertSame(0, $this->repository->count([]));
    }
}
