<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    function updateInfo(Request $request){
        $validatedData = $request->validate([
            'winkelnaam' => 'required|string|max:255',
            'winkelbeschrijving' => 'nullable|string',
            'slug' => 'nullable',
            'winkelafbeelding' => 'nullable|image|max:2048', // Max grootte van 2 MB
            'achtergrondkleur' => 'nullable|string|max:7', // hex kleurcode max 7 karakters
            'tekstkleur' => 'nullable|string|max:7', // hex kleurcode max 7 karakters
        ]);

        $company = Company::find($request->input('companyId'));
        // Sla de gegevens op
        $company->name = $validatedData['winkelnaam'];
        $company->description = $validatedData['winkelbeschrijving'];
        $company->slug = $validatedData['slug'];
        $company->background_color = $validatedData['achtergrondkleur'];
        $company->text_color = $validatedData['tekstkleur'];

        // Sla de afbeelding op en sla de locatie op in de database
        if($request->hasFile('winkelafbeelding')){
            $image = $request->file('winkelafbeelding');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $company->image = $name;
        }

        $company->save();
        return redirect()->back();
    }
}
