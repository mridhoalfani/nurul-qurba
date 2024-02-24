<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\Post;

class Categories extends Component
{
    public $category_name;
    public $selected_category_id;
    public $updateCategoryMode = false;

    public $subcategory_name;
    public $parent_category = 0;
    public $selected_subcategory_id;
    public $updateSubCategoryMode = false;

    public $delete_id;

    protected $listeners = [
        'resetModalForm',
        'deleteCategoryAction',
        'deleteSubCategoryAction',
        'updateCategoryOrdering',
    ];

    public function resetModalForm(){
        $this->resetErrorBag();
        $this->category_name = null;
        $this->subcategory_name = null;
        $this->parent_category =  null;
    }

    public function addCategory(){
        $this->validate([
            'category_name'=>'required|unique:categories,category_name',
        ]);

        $category = new Category();
        $category->category_name = $this->category_name;
        $saved = $category->save();
        
        if($saved){
            $this->dispatch('hideCategoryModel');
            $this->category_name = null;
            $this->dispatch('success');
        }else{
            $this->dispatch('error');
        }
    }

    public function editCategory($id) {
        $category = Category::findOrFail($id);
        $this->selected_category_id = $category->id;
        $this->category_name = $category->category_name;
        $this->updateCategoryMode = true;
        $this->resetErrorBag();
        $this->dispatch('showcategoriesModal');
    }

    public function updateCategory(){
        if($this->selected_category_id){
            $this->validate([
                'category_name'=>'required|unique:categories,category_name,'.$this->selected_category_id,
            ]);

            $category = Category::findOrFail($this->selected_category_id);
            $category->category_name = $this->category_name;
            $updated = $category->save();

            if($updated){
                $this->dispatch('hideCategoryModel');
                $this->updateCategoryMode = false;
                $this->dispatch('success');
            }else{
                $this->dispatch('error');
            }
        }
    }

    public function deleteCategory($id){
        $this->delete_id = $id;
        $this->dispatch('deleteCategory');
    }
    public function deleteCategoryAction(){
        $category = Category::where('id', $this->delete_id)->first();
        $subcategories = SubCategory::where('parent_category', $category->id)->whereHas('posts')->with('posts')->get();

        if( !empty($subcategories) && count($subcategories) > 0 ){
            $totalPosts = 0;
            foreach($subcategories as $subcat){
                $totalPosts += Post::where('category_id', $subcat->id)->get()->count();
            }
            $this->dispatch('error');
        }else{
            SubCategory::where('parent_category', $category->id)->delete();
            $category->delete();
            $this->dispatch('success');
        }
    }

    public function addSubCategory() {
        $this->validate([
            'parent_category'=>'required',
            'subcategory_name'=>'required|unique:sub_categories,subcategory_name',
        ]);

        $subcategory = new SubCategory();
        $subcategory->subcategory_name = $this->subcategory_name;
        $subcategory->slug = Str::slug($this->subcategory_name);
        $subcategory->parent_category = $this->parent_category;
        $saved = $subcategory->save();

        if($saved){
            $this->dispatch('hideSubCategoriesModal');
            $this->parent_category = null;
            $this->subcategory_name = null;
            $this->dispatch('success');
        }else{  
            $this->dispatch('error');
        }
        
    }

    public function editSubCategory($id){
        $subcategory = SubCategory::findOrFail($id);
        $this->selected_subcategory_id = $subcategory->id;
        $this->parent_category = $subcategory->parent_category;
        $this->subcategory_name = $subcategory->subcategory_name;
        $this->updateSubCategoryMode = true;
        $this->resetErrorBag();
        $this->dispatch('showSubCategoriesModal');

    }
    
    public function updateSubCategory(){
        if($this->selected_subcategory_id){
            $this->validate([
                'parent_category'=>'required',
                'subcategory_name'=>'required|unique:sub_categories,subcategory_name,'.$this->selected_subcategory_id,
            ]);

            $subcategory = SubCategory::findOrFail($this->selected_subcategory_id);
            $subcategory->subcategory_name = $this->subcategory_name;
            $subcategory->slug = Str::slug($this->subcategory_name);
            $subcategory->parent_category = $this->parent_category;
            $updated = $subcategory->save();

            if($updated){
                $this->dispatch('hideSubCategoriesModal');
                $this->updateSubCategoryMode = false;
                $this->dispatch('success');
            }else{
                $this->dispatch('error');
            }
        }
    }

    public function deleteSubCategory($id){
        $this->delete_id = $id;
        $this->dispatch('deleteSubCategory');
    }

    public function deleteSubCategoryAction(){
        $subcategory = SubCategory::where('id', $this->delete_id)->first();
        $posts = Post::where('category_id', $subcategory->id)->get()->toArray();

        if( !empty($posts) && count($posts) > 0 ){
            $this->dispatch( 'error');
        }else{
            $subcategory->delete();
            $this->dispatch('success');
        }

    }


    // MASIH ERROR
    // public function updateCategoryOrdering($positions){
    //     // dd($positions);
    //     foreach($positions as $position){
    //         $index = $position[0];
    //         $newPosition = $position[1];
    //         Category::where('id', $index)->update([
    //             'ordering'=>$newPosition
    //         ]);
    //         $this->dispatch('success');
    //     }
    // }

    public function render()
    {
        return view('livewire.categories', [
            'categories'=>Category::orderBy('ordering', 'asc')->get(),
            'subcategories'=>SubCategory::orderBy('ordering', 'asc')->get(),
        ]);
    }
}
