<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainTitle;
use App\Models\Media;
use App\Models\Paragraph;
use App\Models\Subject;
use Illuminate\Http\Request;

class LibraryController extends Controller
{

    public function subjects(Request $request){
        $subjects = Subject::all();
        return view('dashboard.library.subjects', ['subjects' => $subjects]);
    }

    public function createSubject(Request $request){
        $subject = Subject::create([
            'name' => $request->name,
        ]);
        return redirect()->route('subjects');
    }

    public function editSubject(Request $request, $subject_id){
        $subject = Subject::find($subject_id);
        $subject->update([
            'name' => $request->name,
        ]);
        return redirect()->route('subjects');
    }

    public function deleteSubject($subject_id){

        $subject = Subject::find($subject_id);
        $subject?->delete();
        return redirect('subjects');
    }

    public function mainTitles(Request $request, $subject_id){
        $main_titles = MainTitle::where('subject_id', $subject_id)->get();
        return view('dashboard.library.main_titles', ['main_titles' => $main_titles, 'subject_id' => $subject_id]);
    }

    public function createMainTitle(Request $request, $subject_id){
        $main_title = MainTitle::create([
            'title'=> $request->title,
            'subject_id' => $subject_id,
        ]);
        return redirect()->route('main_titles', $subject_id);
    }

    public function editMainTitle(Request $request, $main_title_id){
        $main_title = MainTitle::find($main_title_id);
        $subject_id = $main_title->subject_id;
        $main_title->update([
            'title' => $request->title,
        ]);
        return redirect()->route('main_titles', $subject_id);
    }

    public function deleteMainTitle($main_title_id){

        $main_title = MainTitle::find($main_title_id);
        $subject_id = $main_title->subject_id;
        $main_title?->delete();
        return redirect()->route('main_titles', $subject_id);
    }

    public function paragraphs(Request $request, $main_title_id){
        $paragraphs = Paragraph::where('main_title_id', $main_title_id)->get();
        return view('dashboard.library.paragraphs', ['paragraphs' => $paragraphs, 'main_title_id' => $main_title_id]);
    }

    public function createParagraph(Request $request, $main_title_id){
        $paragraph = Paragraph::create([
            'title' => $request->title,
            'text' => $request->text,
            'main_title_id' => $main_title_id,
        ]);
        return redirect()->route('paragraphs', $main_title_id);
    }

    public function editParagraph(Request $request, $paragraph_id){
        $paragraph = Paragraph::find($paragraph_id);
        $main_title_id = $paragraph->main_title_id;
        $paragraph->update([
            'title' => $request->title,
            'text' => $request->text,
        ]);
        return redirect()->route('paragraphs', $main_title_id);
    }

    public function deleteParagraph($paragraph_id){

        $paragraph = Paragraph::find($paragraph_id);
        $main_title_id = $paragraph->main_title_id;
        $paragraph?->delete();
        return redirect()->route('paragraphs', $main_title_id);
    }

    public function medias(Request $request, $paragraph_id){
        $medias = Media::where('paragraph_id', $paragraph_id)->get();
        return view('dashboard.library.media', ['medias' => $medias, 'paragraph_id' => $paragraph_id]);
    }

    public function createMedia(Request $request, $paragraph_id){
        $media = Media::create([
            'url' => $request->url,
            'type' => $request->type,
            'label' => $request->label,
            'paragraph_id' => $paragraph_id,
        ]);
        return redirect()->route('medias', $paragraph_id);
    }

    public function editMedia(Request $request, $media_id){

        $media = Media::find($media_id);
        $paragraph_id = $media->paragraph_id;
        $media->update([
            'url' => $request->url,
            'type' => $request->type,
            'label' => $request->label,
        ]);
        return redirect()->route('medias', $paragraph_id);
    }

    public function deleteMedia($media_id){

        $media = Media::find($media_id);
        $paragraph_id = $media->paragraph_id;
        $media?->delete();
        return redirect()->route('medias', $paragraph_id);
    }
}
