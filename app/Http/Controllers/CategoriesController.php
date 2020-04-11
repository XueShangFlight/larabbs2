<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category,Request $request,Topic $topic,User $user)
    {

        //读取分类ID关联的话题，并按每15条分页
        $topics = $topic->withOrder($request->order)->where('category_id',$category->id)->with('user','category')->paginate(15);
        //$topics = Topic::where('category_id',$category->id)->paginate(15);

        //传参变量话题和分类到模板中

        // 活跃用户列表
        $active_users = $user->getActiveUsers();
        return view('topics.index',compact('topics','category','active_users'));
    }
}
