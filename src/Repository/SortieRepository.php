<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function rechercheParNom($search){

        return $this->createQueryBuilder('s')
            ->where('s.nom like :nom')
            ->setParameter('nom', '%'.$search.'%')
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function rechercheParDateDebut($dateDebut){

        return $this ->createQueryBuilder('s')
            ->where('s.dateDebut >= :date')
            ->setParameter('date',$dateDebut)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function rechercheParDateFin($dateFin){

        return $this ->createQueryBuilder('s')
            ->where('s.dateDebut <= :date')
            ->setParameter('date',$dateFin)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();

    }

    public function recherchePardateDebutDateFin($dateDebut,$dateFin){
        return $this ->createQueryBuilder('s')
            ->where('s.dateDebut <= :dateF')
            ->setParameter('dateF',$dateFin)
            ->andWhere('s.dateDebut >= :dateD')
            ->setParameter('dateD',$dateDebut)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function rechercheParUserOrga($userId){

        return $this->createQueryBuilder('s')
            ->where('s.organisateur =:orga')
            ->setParameter('orga',$userId)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();

    }

    public function rechercheParUserInscris($userId){
        return $this->createQueryBuilder('s')
            ->innerJoin('s.inscriptions', 'i', 'WITH', 'i.participant =:inscris')
            ->setParameter('inscris',$userId)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function rechercheParUserNonInscris($userId){

        return $this->createQueryBuilder('s')
            ->InnerJoin('s.inscriptions', 'i', 'WITH', 'i.participant !=:inscris')
            ->setParameter('inscris',$userId)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();

    }

    public function rechercheParSortiePassee(){

        return $this->createQueryBuilder('s')
            ->where('s.etat =:etat')
            ->setParameter('etat','PAS')
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();

    }


    public function rechercheParEtatOuv(){

        $date = new \DateTime('now');

        return $this->createQueryBuilder('s')
            ->andWhere('s.etat = :etat')
            ->setParameter('etat','OUV')
            ->getQuery()
            ->getResult();

    }

    // Recherche d'un site sur la page d'accueil
    public function rechercheParSite($site)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.site = :site')
            ->setParameter('site', $site)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult()
            ;

    }

// Recherche d'un nom d'une sortie sur la page d'accueil
    public function recherche($search){
            return $this->createQueryBuilder('s')
            ->where('s.nom like :nom')
            ->setParameter('nom', '%'.$search.'%')
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;

    }


    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
