<?php
namespace App\\Http\\Controllers;
use App\\Models\\Lead;
use Illuminate\\Http\\Request;
class LeadController extends Controller {
    private function rules(): array { return ['name'=>'required|string|max:160','company'=>'nullable|string|max:180','email'=>'nullable|email|max:190','phone'=>'nullable|string|max:50','source'=>'nullable|string|max:80','status'=>'required|in:new,contacted,qualified,proposal,won,lost','value'=>'nullable|numeric|min:0|max:999999999','notes'=>'nullable|string|max:10000']; }
    public function index(Request $request) {
        $query = Lead::query()->latest();
        if ($search = trim((string)$request->query('q'))) $query->where(fn($q)=>$q->where('name','like',"%$search%")->orWhere('company','like',"%$search%")->orWhere('email','like',"%$search%"));
        return view('leads.index',['leads'=>$query->paginate(15)->withQueryString(),'q'=>$search ?? '']);
    }
    public function create() { return view('leads.form',['lead'=>new Lead(),'heading'=>'Add lead']); }
    public function store(Request $request) { $data=$request->validate($this->rules()); $data['created_by']=$request->user()->id; Lead::create($data); return redirect()->route('leads.index')->with('success','Lead created.'); }
    public function edit(Lead $lead) { return view('leads.form',['lead'=>$lead,'heading'=>'Edit lead']); }
    public function update(Request $request, Lead $lead) { $lead->update($request->validate($this->rules())); return redirect()->route('leads.index')->with('success','Lead updated.'); }
    public function destroy(Lead $lead) { $lead->delete(); return redirect()->route('leads.index')->with('success','Lead deleted.'); }
}