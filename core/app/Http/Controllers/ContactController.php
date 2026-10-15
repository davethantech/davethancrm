<?php
namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;
class ContactController extends Controller {
    private function rules(): array {
        return ['first_name'=>'required|string|max:100','last_name'=>'nullable|string|max:100','email'=>'nullable|email|max:190','phone'=>'nullable|string|max:50','job_title'=>'nullable|string|max:150','company_id'=>'nullable|exists:companies,id','notes'=>'nullable|string|max:10000'];
    }
    public function index(Request $request) {
        $search = trim((string) $request->query('q'));
        $query = Contact::with('company')->latest();
        if ($search !== '') $query->where(fn($q) => $q->where('first_name','like',"%{$search}%")->orWhere('last_name','like',"%{$search}%")->orWhere('email','like',"%{$search}%"));
        return view('contacts.index',['contacts'=>$query->paginate(15)->withQueryString(),'q'=>$search]);
    }
    public function create() { return view('contacts.form',['contact'=>new Contact(),'companies'=>Company::orderBy('name')->get(),'heading'=>'Add contact']); }
    public function store(Request $request) {
        $data = $request->validate($this->rules());
        $data['created_by'] = $request->user()->id;
        Contact::create($data);
        return redirect()->route('contacts.index')->with('success','Contact created.');
    }
    public function edit(Contact $contact) { return view('contacts.form',['contact'=>$contact,'companies'=>Company::orderBy('name')->get(),'heading'=>'Edit contact']); }
    public function update(Request $request, Contact $contact) {
        $contact->update($request->validate($this->rules()));
        return redirect()->route('contacts.index')->with('success','Contact updated.');
    }
    public function destroy(Contact $contact) {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success','Contact deleted.');
    }
}