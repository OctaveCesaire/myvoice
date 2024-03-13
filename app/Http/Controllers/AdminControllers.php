<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Candidats;
use App\Models\Elections;
use Illuminate\Http\Request;
use App\Imports\CandidatsImports;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

/************ ZONE STRIPE /************/
use Stripe\Stripe;
use Stripe\Checkout\Session;

class AdminControllers extends Controller
{
    // DashBoard
    function dashboard() {
        return view('dashboard');
    }

    /******************* ZONE POUR Activities *******************/
    function postActivites() {
        $title = "New Activites";
        return view('auth.pages.recapitulation',compact('title'));
    }

    // View Of Activities
    function activities() {
        $title = "Elections";
        $electionPending = Elections::all();
        return view('auth.pages.recapitulation',compact('title','electionPending'));
    }


    // View Of Statistic
    function overview($id){
        $title = "Overview";
        $electionTitle = Elections::findOrFail($id);
        $electionPending = Candidats::where('election_id',$id)->get();
        return view('auth.pages.recapitulation',compact('title','electionPending','electionTitle'));
    }

    function deleteActivity($id) {
        // Supprimer tous les candidats associés à l'élection
        Candidats::join('elections','candidats.election_id','=','elections.id')
                ->where('elections.id', $id)
                ->delete();



        // Trouver et supprimer l'élection elle-même
        $electionTitle = Elections::findOrFail($id);
        if ($electionTitle) {
            $electionTitle->delete();
        }
        // Redirection vers une autre page après la suppression
        return redirect()->route('activities');
    }


    //  Store activities
    public function newActivities(Request $request)
    {
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'choix' => 'required|string',
            'launching_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    // Vérifie si la date de lancement est inférieure à aujourd'hui
                    if (Carbon::parse($value)->lessThan(Carbon::today())) {
                        $fail('The launching date field must be a date after or equal today and before ending date.');
                    }
                },
            ],
            'ending_date' => 'required|date|after_or_equal:launching_date',
            'pays' => 'required|string',
            'file' => 'required|mimes:xls,xlsx,csv,ods',
        ]);

        // Vérification de la validation
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Vérifier si un fichier a été envoyé avec la requête
        if ($request->hasFile('file')) {
            // Vérifier si le fichier est valide
            if ($request->file('file')->isValid()) {
                // Obtenir l'extension du fichier
                $extension = $request->file('file')->extension();
                // Vérifier le type du fichier en fonction de son extension (csv,ods,xls,xlxs)
                if ($extension === 'xls' || $extension === 'xlsx' || $extension === 'ods' || $extension==='csv') {
                    $activities = Elections::create([
                        'title'=> $request->title,
                        'type'=>$request->choix,
                        'launchingDate'=>$request->launching_date,
                        'endingDate'=>$request->ending_date,
                        'pays'=>$request->pays
                    ]);
                    $activitiesID = $activities->getKey(); // ou $postcompanion->id
                    // // Gérez le fichier téléchargé
                    if ($request->hasFile('file')) {
                        $file = $request->file('file');
                        Excel::import(new CandidatsImports($request->choix,$request->pays,$activitiesID),$file);

                    }
                    return redirect('activities');;
                }else {
                    return redirect()->back()->with('message','Fiche incorrecte');
                }
            }else {
                // Le fichier n'est pas valide
                return 'Le fichier n\'est pas valide.';
            }
        }else {
            return redirect()->back()->with('message', 'Aucun fichier n\'a été envoyé.');
        }
    }

    /*************************** ** ZONE DE STRIPE ** ***************************/
    public function createStripeSession(Request $request, $id)
    {
        // Configurez la clé secrète de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Créez une session de paiement Stripe
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        // 'unit_amount' => 69550, // Montant en cents (ex. 10 $ = 1000 cents)
                        'unit_amount' => 0, // Montant en cents (ex. 10 $ = 1000 cents)
                        'product_data' => [
                            'name' => 'Votre produit',
                            'description' => 'Description de votre produit',
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('billing.success'), // URL de redirection après un paiement réussi
            'cancel_url' => route('billing.cancel'), // URL de redirection après une annulation de paiement
        ]);

        // Stockez l'ID du compagnon dans la session
        $request->session()->put('elections_id', $id);

        // Redirigez l'utilisateur vers la page de paiement Stripe
        return redirect()->away($session->url);
    }

    // Méthode pour gérer la réussite du paiement
    public function handlePaymentSuccess(Request $request)
    {
        // Récupérez l'ID du compagnon depuis la session
        $electionsId = $request->session()->get('elections_id');

        // Mettez à jour le statut du compagnon
        $elections = Elections::findOrFail($electionsId);
        $elections->status = "launching";
        $elections->save();

        // Redirigez l'utilisateur vers une page de confirmation de paiement réussi
        return redirect('activities')->with('message','Paiement accepté. Election planifié avec succès.');
    }

    // Méthode pour gérer l'annulation de paiement
    public function handlePaymentCancel()
    {
        // Redirigez l'utilisateur vers une page de confirmation d'annulation de paiement
        return redirect('activities')->with('message','Paiement refusé.Réessayez plus tard.');
        // return view('billing.cancel');
    }
    /*************************** ** ZONE DE STRIPE ** ***************************/

}
