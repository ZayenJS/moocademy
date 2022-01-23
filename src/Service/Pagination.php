<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Pagination
{

  private int $_currentPage = 1;
  private $_entityClass;
  private $_limit = 10;
  private $_criteria = [];
  private $_orderBy = [];

  public function __construct(private EntityManagerInterface $_manager)
  {
  }

  public function getEntityClass()
  {
    return $this->_entityClass;
  }

  public function setEntityClass($entityClass)
  {
    $this->_entityClass = $entityClass;

    return $this;
  }

  public function getCriteria()
  {
    return $this->_criteria;
  }

  public function setCriteria(array $criteria)
  {
    $this->_criteria = $criteria;

    return $this;
  }

  public function getOrderBy()
  {
    return $this->_orderBy;
  }

  public function setOrderBy(array $orderBy)
  {
    $this->_orderBy = $orderBy;

    return $this;
  }

  public function getLimit()
  {
    return $this->_limit;
  }

  public function setLimit($limit)
  {
    $this->_limit = $limit;

    return $this;
  }

  public function getCurrentPage()
  {
    return $this->_currentPage;
  }

  public function setCurrentPage($page)
  {
    if (is_numeric($page) && $page > 0) {
      $this->_currentPage = $page;
    }

    return $this;
  }

  public function getData()
  {
    if (empty($this->_entityClass)) {
      throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! ");
    }
    if ($this->_currentPage < 1) {
      throw new NotFoundHttpException('Page "' . $this->_currentPage . '" inexistante.');
    }

    $offset = $this->_limit * ($this->_currentPage - 1);

    $repository = $this->_manager->getRepository($this->_entityClass);
    $paginatedData = $repository->findBy(
      $this->_criteria,
      $this->_orderBy,
      $this->_limit,
      $offset
    );

    $total = count($repository->findBy($this->_criteria));

    $pages = ceil($total / $this->_limit);

    return [
      'data' => $paginatedData,
      'total' => $total,
      'pages' => $pages,
    ];
  }
}