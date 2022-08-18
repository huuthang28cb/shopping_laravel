<?php

namespace App\Http\Controllers;

use App\Category;
use App\Components\Recusive;
use App\Http\Requests\ProductAddRequest;
use App\Product;
use App\ProductImage;
use App\ProductTag;
use App\Tag;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    use StorageImageTrait;
    private $category;
    private $product;
    private $productImage;
    private $tag;
    private $productTag;
    public function __construct(Category $category, Product $product, ProductImage $productImage,
                                Tag $tag, ProductTag $productTag)
    {
        $this->category=$category;
        $this->product=$product;
        $this->productImage=$productImage;
        $this->tag=$tag;
        $this->productTag=$productTag;
    }

    public function index()
    {
        $products = $this->product->latest()->paginate(5);
        return view('admin.product.index', compact('products'));
    }
    public function getCategory($parentId)
    {
        $data= $this->category->all();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->categoryRecusive($parentId);
        return $htmlOption;
    }
    public function create()
    {
        $htmlOption = $this->getCategory($parentId='');
        return view('admin.product.add', compact('htmlOption'));
    }
    public function store(ProductAddRequest $request) // tại vì mình validate rồi nên gọi cái request kia ra
    {
        try{
            // https://laravel.com/docs/9.x/database#manually-using-transactions
            DB::beginTransaction(); // Bắt đầu cho việc transaction

            $dataProductCreate = [
                'name'=>$request->name,
                'price'=>$request->price,
                'content'=>$request->contents,
                'user_id'=>auth()->id(),
                'category_id'=>$request->category_id,
            ];
            $dataUploadFeatureImage = $this->storageTraitUpload($request, 'feature_image_path', 'product');
            if(!empty($dataUploadFeatureImage)){
                $dataProductCreate['feature_image_name'] = $dataUploadFeatureImage['file_name'];
                $dataProductCreate['feature_image_path'] = $dataUploadFeatureImage['file_path'];
            }
            $product = $this->product->create($dataProductCreate);

            // insert data to product_images
            if($request->hasFile('image_path')){
                foreach ($request->image_path as $fileItem){
                    $dataProductImageDetail = $this->storageTraitUploadMutiple($fileItem, 'product');
                    $product->images()->create([
                        'image_path' => $dataProductImageDetail['file_path'],
                        'image_name' => $dataProductImageDetail['file_name']
                    ]);
                }
            };

            // insert tags for product
            if(!empty($request->tags)){
                foreach ($request->tags as $tagItem){
                    // insert to tags
                    $tagInstance = $this->tag->firstOrCreate(['name'=>$tagItem]); /* firstOrCreate: nếu tên tông tại thì trả về chính nó mà k thêm vào database*/
                    $tagIds[]= $tagInstance->id;
                }
            }

            // truyền các id của mình vào đã post, $tagIds là các tag id cần insert rồi
            $product->tags()->attach($tagIds);

            DB::commit(); // Khi nào chạy đến commit nay thì dữ liệu mới được insert vào DB. Nếu lỗi thì nó sẽ chạy vào rollBack

            return redirect()->route('product.index');

        }catch (\Exception $exception){
            DB::rollBack(); // Khi có bug, k thực hiện được thì rollBack lại
            Log::error('Message:' . $exception->getMessage() . '---Line: '. $exception->getLine());
        }
    }
    public function edit($id)
    {
        $product = $this->product->find($id);
        $htmlOption = $this->getCategory($product->category_id);
        return view('admin.product.edit', compact('htmlOption', 'product'));
    }

    // UPDATE FUNCTION
    public function update(Request $request,$id)
    {
        try{
            // https://laravel.com/docs/9.x/database#manually-using-transactions
            DB::beginTransaction(); // Bắt đầu cho việc transaction

            $dataProductUpdate = [
                'name'=>$request->name,
                'price'=>$request->price,
                'content'=>$request->contents,
                'user_id'=>auth()->id(),
                'category_id'=>$request->category_id,
            ];
            $dataUploadFeatureImage = $this->storageTraitUpload($request, 'feature_image_path', 'product');
            if(!empty($dataUploadFeatureImage)){
                $dataProductUpdate['feature_image_name'] = $dataUploadFeatureImage['file_name'];
                $dataProductUpdate['feature_image_path'] = $dataUploadFeatureImage['file_path'];
            }
            $this->product->find($id)->update($dataProductUpdate);

            // data trả về của nó k phải là 1 instant của cái product, bởi vì khi update th sẽ trả về true/false
            // nên sẽ duùng:
            $product = $this->product->find($id); // Rồi mới vào instant 1 cái product dc

            // insert data to product_images
            if($request->hasFile('image_path')){
                $this->productImage->where('product_id', $id)->delete();
                foreach ($request->image_path as $fileItem){
                    $dataProductImageDetail = $this->storageTraitUploadMutiple($fileItem, 'product');
                    $product->images()->create([
                        'image_path' => $dataProductImageDetail['file_path'],
                        'image_name' => $dataProductImageDetail['file_name']
                    ]);
                }
            };

            // insert tags for product
            if(!empty($request->tags)){
                foreach ($request->tags as $tagItem){
                    // insert to tags
                    $tagInstance = $this->tag->firstOrCreate(['name'=>$tagItem]); /* firstOrCreate: nếu tên tông tại thì trả về chính nó mà k thêm vào database*/
                    $tagIds[]= $tagInstance->id;
                }
            }

            // truyền các id của mình vào đã post, $tagIds là các tag id cần insert rồi
            $product->tags()->sync($tagIds); // nêu có rồi thì k insert nữa, trùng thì tât nhiên là k xóa rồi

            DB::commit(); // Khi nào chạy đến commit nay thì dữ liệu mới được insert vào DB. Nếu lỗi thì nó sẽ chạy vào rollBack

            return redirect()->route('product.index');

        }catch (\Exception $exception){
            DB::rollBack(); // Khi có bug, k thực hiện được thì rollBack lại
            Log::error('Message:' . $exception->getMessage() . '---Line: '. $exception->getLine());
        }
    }

    // DELETE FUNCTION
    public function delete($id)
    {
        try {
            $this->product->find($id)->delete();
            return response()->json([
                'code'=>200,
                'message'=>'success'
            ], 200);
        }catch (\Exception $exception){
            Log::error('Message:' . $exception->getMessage() . '---Line: '. $exception->getLine());
            return response()->json([
                'code'=>500,
                'message'=>'fail'
            ], 500);
        }
    }
}
