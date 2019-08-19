<?php

namespace App\Http\Controllers\Blog;

use App\Http\Resources\ArticlesResource;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller as BaseController;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Blog\ArticleCreateRequest;
use App\Http\Requests\Blog\ArticleUpdateRequest;
use App\Repositories\Eloquent\Blog\ArticleRepositoryEloquent as ArticleRepository;
use App\Validators\Blog\ArticleValidator;
use Illuminate\Http\Response;
use Exception;

/**
 * Class CategoriesController.
 *
 * @package namespace App\Http\Controllers\Blog;
 */
class ArticlesController extends BaseController
{
    /**
     * @var ArticleRepository
     */
    protected $repository;

    /**
     * @var ArticleValidator
     */
    protected $validator;

    /**
     * ArticlesController constructor.
     *
     * @param ArticleRepository $repository
     * @param ArticleValidator $validator
     */
    public function __construct(ArticleRepository $repository, ArticleValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $articles = $this->repository->all();
//        $articles = $this->repository->paginate($limit = null, $columns = ['*']);

        $response = [
            'code' => Response::HTTP_OK,
            'message' => 'Articles retrived.',
            'result'    => $articles,
        ];

        if (request()->wantsJson()) {

            return response()->json($response);
        }

        return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ArticleCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ArticleCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $article = $this->repository->create($request->all());

            $response = [
                'code' => Response::HTTP_CREATED,
                'message' => 'Article created.',
                'result'    => $article,
            ];

            if ($request->wantsJson()) {

                return response()->json($response, Response::HTTP_CREATED );
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $article = $this->repository->with('user')->find($id);

            $response = [
                'code'    => Response::HTTP_OK,
                'message' => 'Article found.',
                'result'  => new ArticlesResource($article),
            ];

            if (request()->wantsJson()) {

                return response()->json($response);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (ValidatorException $e) {

            if (request()->wantsJson()) {
                return response()->json([
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ArticleUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ArticleUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $article = $this->repository->update($request->all(), $id);

            $response = [
                'code' => Response::HTTP_OK,
                'message' => 'Article updated.',
                'result'    => $article->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {
                return response()->json([
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->repository->delete($id);

            if (request()->wantsJson()) {

                return response()->json(['code' => Response::HTTP_OK, 'message' => 'Article is deleted.', 'deleted' => $deleted]);
            }

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'API returns JSON format only.']);

        } catch (Exception $exception) {

            return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'Not an article found.']);
        }
    }
}
