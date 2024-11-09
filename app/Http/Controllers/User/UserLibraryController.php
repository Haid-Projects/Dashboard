<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Paragraph;
use App\Models\Subject;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserLibraryController extends Controller
{
    use GeneralTrait;

    /**
     * Get all subjects
     */
    public function subjects(){
        $subjects = DB::table('subjects')->orderBy('name')->get(['id', 'name']);
        return $this->returnSuccessData($subjects, 'all titles in a subject ordered ascending ', 200);
    }

    /**
     * Get Main titles in a subject
     */
    public function mainTitles($subject_id){
        $main_titles = DB::table('main_titles')
                    ->where('subject_id', '=', $subject_id)
                    ->orderBy('title')
                    ->get(['id', 'title']);
        return $this->returnSuccessData($main_titles, 'all titles in a subject ordered ascending ', 200);
    }

    /**
     * Get paragraphs and media in a title
     */
    public function content($main_title_id){
        $ps = Paragraph::where('main_title_id', $main_title_id)->get(['id', 'title', 'text']);
        $content = array();
        foreach ($ps as $p){
            $m = Media::where('paragraph_id', $p->id)->get(['id', 'type', 'url', 'label']);
            $content[] = ['id' => $p->id,'title' => $p->title,'text' => $p->text, 'media' => $m];
        }
        return $this->returnSuccessData($content, 'all paragraphs and media in a title ordered ascending ', 200);
    }
}
