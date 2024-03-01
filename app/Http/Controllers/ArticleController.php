<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // count()
        $totalArticles = Article::count();

        // count with where
        $totalPublishedArticles = Article::where('is_published', true)->count();

        // countBy with integers columns
        $totalPerUser = Article::pluck('user_id')->countBy();
        // dd($totalPerUser);

        // max()
        $maxToRead = Article::max('min_to_read');
        $maxToReadWithWhere = Article::whereBetween('user_id', [20,30])->max('min_to_read');

        // min
        $minToRead = Article::min('min_to_read');
        // dd($minToRead);

        $median = Article::pluck('min_to_read')->median();
        // dd($median);

        // most common id
        $userWithMostArticles = Article::pluck('user_id')->mode();
        $userWithMostPublishedArticles = Article::where('is_published', true)
            ->pluck('user_id')
            ->mode();

        // inRandomOrder
        $randomOrder = Article::inRandomOrder()->value('id');
        // dd($randomOrder);

        // sum
        $totalMinToRead = Article::sum('min_to_read');
        // dd($totalArticles);

        $collection = collect(
            array(
                array('name' => 'Alice', 'age' => 25),
                array('name' => 'Bob', 'age' => 30),
                array('name' => 'Charlie', 'age' => 28),
                array('name' => 'Diana', 'age' => 22),
                array('name' => 'Ethan', 'age' => 35),
                array('name' => 'Fiona', 'age' => 20),
                array('name' => 'George', 'age' => 40),
                array('name' => 'Helen', 'age' => 27),
                array('name' => 'Isaac', 'age' => 32),
                array('name' => 'Julia', 'age' => 21),
            )
        );

        // where

        $ageAboveTwenty = $collection->where('age', ">", 20);
        // dd($ageAboveTwenty);

        // median age
        $medianAge = $collection->pluck('age')->median();
        // dd($medianAge);

        $articles = Article::where('is_published', true)
            ->where('min_to_read', 9)->get();

        // where with callback
        $publishedArticles = Article::where(function ($query) {
            return $query->where('is_published', true);
        });

        // whereStrict
        $users = $collection->whereStrict("name", "Alice");
        // dd($users);

        // whereBetween
        $articles = Article::whereBetween('min_to_read',[5, 9])
        ->whereBetween('created_at',['2024-01-01', '2024-03-31'])
        ->get();

        // where In, whereNotIn
        $roles = ["admin", "user"];
        $users = User::whereIn('role', $roles)->get();
        // $users = User::whereIn('role', $roles)->count();

        $users = User::whereNotIn('role', $roles)
        ->where('email_verified_at', today())
        ->get();

        // whereNull
        Article::where('min_to_read', '>', 8)
            ->update([
                'excerpt' => null
            ]);

        $articles = Article::whereNull('excerpt')->get();

        $collection2 = collect([
            ["name" => "Julian"],
            ["name" => null]
        ]);

        $collection2->whereNull('name');

        // whereNotNull
        $filledUsers = $collection2->whereNotNull('name');

        // Date filters

        // whereDate
        $articles = Article::whereDate('created_at', '2024-03-01')->get();

        // whereDay
        $articles = Article::whereDay('created_at', 1)->get();

        // whereMonth
        $articles = Article::whereMonth('created_at', 3)->get();

        // whereYear
        $articles = Article::whereYear('created_at', 2024)->get();

        // whereTime
        $articles = Article::whereTime('created_at', '>=', "08:00:00")->get();

        // Collection Fucntions

        $collection = collect([1,2,3,4,'',null,false,0,[]]);

        // filter

        $new_collection = $collection->filter(function ($value, $key) {
            return $value > 2 && $key < 7;
        });

        $articles = Article::where('is_published', true)->get();
        $longArticles = $articles->filter(function ($article) {
            return $article->min_to_read > 8;
        });

        $filleds = $collection->reject(function ($value, $key) {
            return empty($value);
        });

        $emptys = $collection->filter(function ($value, $key) {
            return empty($value);
        });

        $articles = Article::all();

        $nonEmptyExcerptArticle = $articles->reject(function ($article) {
            return empty($article->excerpt);
        });


        $playerCollection = collect([
            "name" => "Kevin de Bruyne",
            "age" => 31,
            "club" => "Manchester City"
        ]);


        // contains
        $collectionContainsTwenty = $collection->contains(20);


        // except
        $filtered = $playerCollection->except("age");
        // in eloquent model there is not except. we can use select
        $articles = Article::where('user_id', 24)
            ->select('excerpt', 'description')
            ->get();


        // only
        $filtered = $playerCollection->only('name', 'club');


        // map and mapWithKeys
        $players = collect([
            ['name' => "Lionel Messi", "position" => "Forward"],
            ['name' => "Kylian Mbappe", "position" => "Forward"],
            ['name' => "Neymar Jr.", "position" => "Forward"],
        ]);

        $mapped = $players->map(function ($player) {
            $player["team"] = "PSG";
            return $player;
        });

        $articles = Article::with('user')
            ->get()
            ->map(function ($article) {
                return [
                    "id" => $article->id,
                    "title" => $article->title,
                    "created_at" => $article->created_at->format("d/m/Y"),
                    "user_name" => $article->user->name,
                    "user_email" => $article->user->email
                ];
            });

        $articles = Article::with('user')->get()->mapWithKeys(function ($article) {
            return [
                $article->id => [
                    "id" => $article->id,
                    "title" => $article->title,
                    "created_at" => $article->created_at->format("d/m/Y"),
                    "user_name" => $article->user->name,
                    "user_email" => $article->user->email
                ]
            ];
        });


        // pluck()
        $articles = Article::pluck('title');

        // keyBy()
        $articles = Article::all();
        $articlesById = $articles->keyBy('id');

        $collection = collect([
            8,4,3,5,'Dary','Developer',null, false,[]
        ]);

        // push() add one ou=r multiple values in a collection
        $collection->push('Laravel', [1,2,3]);

        // put() add a item in a collection
        $collection->put("name", 'John Dpe');
        $collection->put("name", 'John Doe')->put('age', 22);

        // forget() - remove a value in collection
        $collection->forget(5)->forget(1)->forget(0);

        // pop() - remove last item of the collection
        $collection->pop();

        // shift() - removes the first element of the collection
        $collection->shift();

        // concat() and zip
        $collection1 = collect([
            "Barcelona", "London", "Amsterdam"
        ]);

        $collection2 = collect([
            "Spain", "United Kingdom"
        ]);

        // contat()
        $combined = $collection1->concat($collection2);

        // zip()
        $zipped = $collection1->zip($collection2);
        // dd($zipped);

        $orders = collect([
            [
                "id" => 1,
                "items" => [
                    ["name" => "widget", 'price' => 10],
                    ["name" => "Gizmo", 'price' => 5]
                ]
            ],
            [
                "id" => 2,
                "items" => [
                    ["name" => "Thing", 'price' => 15],
                    ["name" => "Doodad", 'price' => 20]
                ]
            ]
        ]);

        // collapse()
        $items = $orders->pluck('items')->collapse();
        // dd($items);

        // split()
        $posts = collect([]);

        for ($i=1; $i <= 1000; $i++) {
            $posts->push(["title" => "Post " . $i, "body" => "Body test " . $i]);
        }

        $chunks = $posts->split(50);
        // dd($chunks);

        $collection = collect([2,2,5,4,6,4,5,9,1,0]);

        // sort()
        $sorted = $collection->sort();

        // sortDesc()
        $sortedByDesc = $collection->sortDesc();

        $collection = collect(
            array(
                array('name' => 'Alice', 'age' => 25),
                array('name' => 'Bob', 'age' => 30),
                array('name' => 'Charlie', 'age' => 28),
                array('name' => 'Diana', 'age' => 22),
                array('name' => 'Ethan', 'age' => 35),
                array('name' => 'Fiona', 'age' => 20),
                array('name' => 'George', 'age' => 40),
                array('name' => 'Helen', 'age' => 27),
                array('name' => 'Isaac', 'age' => 32),
                array('name' => 'Julia', 'age' => 21),
            )
        );

        // sortBy()
        $sorted = $collection->sortBy('age');

        // sortByDesc for key value array
        $sorted = $collection->sortByDesc('age');

        // sortKeys
        $sorted = $collection->sortKeys();

        // sortKeysDesc
        $sorted = $collection->sortKeysDesc();
        dd($sorted);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
