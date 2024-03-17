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

        public function __construct() {
            $this->count_election = DB::table('elections')->where('status','launching')->get()->count();
        }

        /*******************************************************************
         *  @ListeForElections : Called to print into select balise
         *  possibility to see all of elections that start and choose that
        ********************************************************************/

        function ListeForElections(){
            $tab = [];
            $election = DB::table('elections')->where('status','launching')
                                              ->where('pays',Auth::user()->pays)
                                              ->get();
            foreach ($election as $key => $value) {
                $tab[$key] = $value->type;
            }
            return view('layouts.selecteCandidat',compact('tab'));
        }

        /***************************************************************
         * @affiche : This function is called to favorite selection of
         * the type of vote that guest want to express themselve 2002
         * following type that he select before.
         * $Request : provide to ListeForElections
        ****************************************************************/

        function affiche(Request $request){
            $candidatAll = DB::table('candidats')->join('elections', 'candidats.election_id', '=', 'elections.id')
                                                 ->select('candidats.id', 'candidats.fullName')
                                                 ->where('elections.status', 'launching')
                                                 ->where('elections.type',$request->Type_Election)
                                                 ->where('elections.pays',Auth::user()->pays)
                                                 ->get();

            return view('home', compact('candidatAll'));
        }

        /***********************************************************************
         * @home :  This function is used to print all of candidats that
         * attempt into the election. It's the first functions that is called
         * when the guest authentifated. Specially,to control number for elect°
         * for directing to views select.
        ************************************************************************/

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
                                                    ->get();
            }
            return view('home', compact('candidatAll'));
        }

        /**************************************************************************
         * @vote : This function is called to allow the vote voice into database
         * This function used the protocol http in local but the method post.
         * @0703 : Review importance for the new database.
         * $Request provide to home
        ***************************************************************************/
        public function vote(Request $request)
        {
            // if($this->count_election >= 2){
            // }
            // else{
            // }
            $voice = Candidats::findOrFail($request->vote_for);
            $voice->nbreDeVoix += 1;
            $voice->update();


            Auth::user()->statut = 'done';
            Auth::user()->update();
            $this->count_election -= 1;

            /****************************************************************************
             * If there are many elections in the guest's country, used table VoteDone
            ****************************************************************************/
            if ($this->count_election > 2) {

                Auth::user()->statut == 'null';
                return redirect()->back();
            } else {
                Auth::user()->statut = 'done';
                Auth::user()->update();
                return redirect('confirm');
            }
        }

        /**********************************************************************
         *  @confirm : is the view that show guest if he vote still.
        ***********************************************************************/
        public function confirm()
        {
            return view('layouts.votedone');
        }
    }
