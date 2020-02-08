<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreItem;
use App\Http\Requests\API\UpdateItem;
use App\Item;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Item::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreItem $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItem $request)
    {
        return Item::create($request->only(['title']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItem $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItem $request, $id)
    {
        $item = Item::find($id);
        $item->update($request->only(['title']));

        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Item::destroy($id);
    }
}
