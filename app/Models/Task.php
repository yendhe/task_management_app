<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['subject', 'description', 'start_date', 'due_date', 'status', 'priority'];

    public function checkNameExists($name)
    {
        return $this->where(['subject' => $name])->first();
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function getAll($input)
    {
        return $this::with('notes.attachments') //Retrieve tasks which have minimum one note attached
            ->withCount('notes')->orderByDesc('notes_count')          //Order: Priority "High" First, Maximum Count of Notes
            ->orderBy('priority', 'desc')
            ->orderBy('notes_count', 'desc')
            ->when($input->has('search_keywords'), function ($query) use ($input) { //Filter: filter[status], filter[due_date], filter[priority], filter[notes]
                $searchs = json_decode($input->search_keywords, true);

                foreach ($searchs as $val) {
                    if (isset($val['key']) && isset($val['value'])) {

                        switch ($val['key']) {
                            case 'status':
                                $query->where('status', 'like', '%' . $val['value'] . '%');
                                break;
                            case 'due_date':
                                $query->where('due_date', $val['value']);
                                break;
                            case 'priority':
                                $query->where('priority', 'like', '%' . $val['value'] . '%');
                                break;
                            case 'note':
                                $query->where('note', 'like',  '%' . $val['value'] . '%');
                                break;
                        }
                    }
                }

                return $query;
            })
            ->get();
    }
}
