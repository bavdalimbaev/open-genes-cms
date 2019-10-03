<?php
namespace genes\infrastructure\repository;

use yii\web\NotFoundHttpException;

interface GeneRepositoryInterface
{
    /**
     * @param $geneId
     * @return array
     * @throws NotFoundHttpException
     */
    public function getGene($geneId): array;
}