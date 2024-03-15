<?php
    namespace App\Http\Controllers;

    use App\Models\Candidats;
    // use App\Models\Elections;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    class AuthControllers extends Controller
    {
        private $count_election;

        // public function __construct()
        // {
        //     $this->middleware(function ($request, $next) {
        //         $this->count_election = Elections::where('status', 'launching')->count();
        //         return $next($request);
        //     });
        // }
        public function __construct() {
            $this->count_election = DB::table('elections')->where('status','launching')->get()->count();
        }

        function selecteCandidat(){
            $tab = [];
            $election = DB::table('elections')->where('status','launching')
                                              ->where('pays',Auth::user()->pays)
                                              ->get();
            foreach ($election as $key => $value) {
                $tab[$key] = $value->type;
            }
            return view('layouts.selecteCandidat',compact('tab'));
        }

        function affiche(Request $request){
            dd($request);

        }

        public function home()
        {
            // TODO: Review l'affichage
            if($this->count_election >=2){
                return redirect('selection');
            }
            else{
                $candidatAll = DB::table('candidats')->join('elections', 'candidats.election_id', '=', 'elections.id')->select('candidats.id', 'candidats.fullName')
                                                    ->where('elections.status', 'launching')
                                                    ->where('elections.pays',Auth::user()->pays)
                                                    // ->groupBy('elections.type')
                                                    // ->orderByDesc('candidats.nbreDeVoix')
                                                    ->get();
            }
            return view('home', compact('candidatAll'));
        }

        public function vote(Request $request)
        {

            $voice = Candidats::findOrFail($request->vote_for);
            // dd($voice);
            $voice->nbreDeVoix += 1;
            $voice->update();
            Auth::user()->statut = 'done';
            Auth::user()->update();
            $this->count_election -= 1;
            if ($this->count_election > 2) {

                Auth::user()->statut == 'null';
                return redirect()->back();
            } else {
                return redirect('confirm');
            }
        }

        public function confirm()
        {
            return view('layouts.votedone');
        }
    }
