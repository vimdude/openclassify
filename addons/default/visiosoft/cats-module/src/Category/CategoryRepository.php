<?php namespace Visiosoft\CatsModule\Category;

use Anomaly\Streams\Platform\Model\Cats\CatsCategoryEntryModel;
use Visiosoft\AdvsModule\Adv\Contract\AdvRepositoryInterface;
use Visiosoft\CatsModule\Category\Contract\CategoryRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryRepository;

class CategoryRepository extends EntryRepository implements CategoryRepositoryInterface
{

    /**
     * The entry model.
     *
     * @var CategoryModel
     */
    protected $model;
    protected $advRepository;

    /**
     * Create a new CategoryRepository instance.
     *
     * @param CategoryModel $model
     * @param AdvRepositoryInterface $advRepository
     */
    public function __construct(CategoryModel $model, AdvRepositoryInterface $advRepository)
    {
        $this->model = $model;
        $this->advRepository = $advRepository;
    }

    public function findById($id)
    {
        return $this->model->orderBy('created_at', 'DESC')->where('cats_category.id', $id)->first();
    }

    public function mainCats()
    {
        return $this->model->where('parent_category_id', null)->orderBy('sort_order')->get();
    }

    public function getItem($cat)
    {
        return $this->model->where('cats_category.id', $cat)->first();
    }

    public function getCatById($id)
    {
        return $this->model->where('cats_category.id', $id)->where('deleted_at', null)->orderBy('sort_order')->get();
    }

    public function getSubCatById($id)
    {
        $cats = $this->model->newQuery()
            ->where('parent_category_id', $id)
            ->get();

        foreach ($cats as $cat) {
            $subCount = $this->model->newQuery()->where('parent_category_id', $cat->id)->count();
            $cat->hasChild = !!$subCount;
        }

        return $cats;
    }

    public function getSingleCat($id)
    {
        return CatsCategoryEntryModel::query()->where('cats_category.id', $id)->first();
    }

    public function findBySlug($slug)
    {
        return $this->model->orderBy('created_at', 'DESC')->where('slug', $slug)->first();
    }

    public function getCategories()
    {
        return $this->model->orderBy('sort_order')->get();
    }

    public function removeCatFromAds($category)
    {
        $catLevelNum = 1;
        if (!is_null($category->parent_category_id)) {
            $catLevelNum = $this->model->getCatLevel($category->id);
        }
        $catLevelText = "cat" . $catLevelNum;

        $advs = $this->advRepository->newQuery()->where($catLevelText, $category->id)->get();
        foreach ($advs as $adv) {
            $nullableCats = array();
            for ($i = $catLevelNum; $i <= 10; $i++) {
                $nullableCats['cat' . $i] = null;
            }
            $adv->update($nullableCats);
        }
    }

    public function DeleteCategories($id)
    {
        if (!is_null($category = $this->find($id))) {
            // Remove deleted category from ads
            $this->removeCatFromAds($category);

            // Delete the category
            $this->model->find($id)->delete();

            // Delete the subcategories
            $this->model->deleteSubCategories($id);
        }
    }
}
