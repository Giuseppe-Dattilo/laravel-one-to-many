<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $type_ids = Type::select('id')->pluck('id')->toArray();
        $type_ids[] = null;

       for($i = 0; $i < 15; $i++){
            $project = new Project();

            $project->type_id = Arr::random($type_ids);
            $project->name = $faker->words(3, true);
            $project->description = $faker->paragraphs(15, true);
            // $project->image = $faker->imageUrl(250, 250);
            $project->prog_url = $faker->url();
            $project->is_published = $faker->boolean();
            $project->save();
       }
    }
}
