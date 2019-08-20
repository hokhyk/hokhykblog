<?php

namespace App\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class BaseArticlesCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
class BaseArticlesRequestCriteria implements CriteriaInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * BaseCompanyCriteria constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        //   Article title
        if ($this->request->get('title')) {
            $model = $model->where('title', 'like', '%' . $this->request->get('title') . '%');
        }

        //  Article content
        if ($this->request->get('article_content')) {
            $model = $model->where('article_content', 'like', '%' . $this->request->get('article_content') . '%');
        }

        //  Author ID
        if ($this->request->get('user_id')) {
            $model = $model->where('user_id', '=', $this->request->get('user_id'));
        }

        //TODO: Time span query for articles including updated_at and created_at.
        //Updated_at
        //Created_at

        return $model;
    }
}
