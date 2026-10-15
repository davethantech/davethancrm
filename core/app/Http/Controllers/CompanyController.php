<?php
namespace App\Http\Controllers;
use App\Models\Company;
use Illuminate\Http\Request;
class CompanyController extends Controller {
    private function rules(): array {
        return ['name'=>'required|string|max:180','website'=>'nullable|url|max:255','industry'=>'nullable|string|max:120','email'=>'nullable|email|max:190','phone'=>'nullable|string|max:50','address'=>'nullable|string|max:255','notes'=>'nullable|string|max:10000'];
    }
    public function index(Request $request) {
        $search = trim((string) $request->query('q'));
        $query = Company::query()->latest();
        if ($search !== '') $query->where(fn($q) => $q->where('name','like',"%{$search}%")->orWhere('industry','like',"%{$search}%")->orWhere('email','like',"%{$search}%"));
        return view('companies.index',['companies'=>$query->paginate(15)->withQueryString(),'q'=>$search]);
    }
    public function create() { return view('companies.form',['company'=>new Company(),'heading'=>'Add company']); }
    public function store(Request $request) {
        $data = $request->validate($this->rules());
        $data['created_by'] = $request->user()->id;
        Company::create($data);
        return redirect()->route('companies.index')->with('success','Company created.');
    }
    public function edit(Company $company) { return view('companies.form',['company'=>$company,'heading'=>'Edit company']); }
    public function update(Request $request, Company $company) {
        $company->update($request->validate($this->rules()));
        return redirect()->route('companies.index')->with('success','Company updated.');
    }
    public function destroy(Company $company) {
        $company->delete();
        return redirect()->route('companies.index')->with('success','Company deleted.');
    }
}