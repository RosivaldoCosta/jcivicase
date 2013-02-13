using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class medeval : Form
    {
        private string _hallucination;

        public string hallucination
        {
            get { return _hallucination; }
            set { _hallucination = value; }
        }
        private int _Active = 1;
        private Int64 _medevalid = Convert.ToInt64(DateTime.Now.TimeOfDay.TotalMilliseconds);
        private string _clientname; //[clientname] [varchar](50) null,
	    private string _clientcode; //[clientcode] [varchar](50) null,
	    private string _date; //[date] [varchar](20) null,
	    private string _therapist; //[therapist] [varchar](50) null,
	    private string _medicationevaluation; //[medicationevaluation] [varchar](10) null,
	    private string _rxreview; //[rxreview] [varchar](10) null,
	    private string _aims; //[aims] [varchar](10) null,
	    private string _instituationalpharmacy;//[instituationalpharmacy] [varchar](10) null,
	    private string _nameofpharmacy; // [varchar](50) null,
	    private string _medication; //[medication1] [varchar](255) null,
	    private string _medication2; //[medication2] [varchar](255) null,
	    private string _medication3; ///[medication3] [varchar](255) null,
	    private string _medication4; //[medication4] [varchar](255) null,
	    private string _dose1; //[dose1] [varchar](15) null,
	    private string _dose2; //[dose2] [varchar](15) null,
	    private string _dose3; //[dose3] [varchar](15) null,
	    private string _dose4; //[dose4] [varchar](15) null,
	    private string _rte1; //[rte1] [varchar](255) null,
	    private string _rte2; //[rte2] [varchar](255) null,
	    private string _rte3; //[rte3] [varchar](255) null,
	    private string _rte4; //[rte4] [varchar](255) null,
	    private string _freq1; //[freq1] [varchar](255) null,
	    private string _freq2; //[freq2] [varchar](255) null,
	    private string _freq3; //[freq3] [varchar](255) null,
	    private string _freq4; //[freq4] [varchar](255) null,
	    private string _startdate1; //[startdate1] [varchar](20) null,
	    private string _startdate2; //[startdate2] [varchar](20) null,
	    private string _startdate3; //[startdate3] [varchar](20) null,
	    private string _startdate4; //[startdate4] [varchar](20) null,
	    private string _enddate1; //[enddate1] [varchar](20) null,
	    private string _enddate2; //[enddate2] [varchar](20) null,
	    private string _enddate3; //[enddate3] [varchar](20) null,
	    private string _enddate4; //[enddate4] [varchar](20) null,
	    private string _targetsymptoms1; //[targetsymptoms1] [varchar](255) null,
	    private string _targetsymptoms2; //[targetsymptoms2] [varchar](255) null,
	    private string _targetsymptoms3; //[targetsymptoms3] [varchar](255) null,
	    private string _targetsymptoms4; //[targetsymptoms4] [varchar](255) null,
	    private string _sideeffects; //[sideeffects1] [varchar](255) null,
	    private string _sideeeffects2; //[sideeffects2] [varchar](255) null,
	    private string _sideeffects3; ///[sideeffects3] [varchar](255) null,
	    private string _sideffects4; //[sideffects4] [varchar](255) null,
	    private string _informedconsent; //[informedconsent] [varchar](5) null,
	    private string _bp; //[bp] [varchar](50) null,
	    private string _p;//[p] [varchar](50) null,
	    private string _rr;//[rr] [varchar](50) null,
	    private string _temp; //[temp] [varchar](50) null,
	    private string _weight; //[weight] [varchar](15) null,
	    private string _age; //[age] [varchar](5) null,
	    private string _accompaniedby; //[accompaniedby] [varchar](50) null,
	    private string _status; //[status] [varchar](15) null,
	    private string _comments; ///[comments] [varchar](255) null,
	    private string _rxcompliance; //[rxcompliance] [varchar](10) null,
	    private string _nervous; //[nervous] [varchar](5) null,
	    private string _jitters; //[jitters] [varchar](5) null,
	    private string _somolence; //[somolence] [varchar](5) null,
	    private string _insomnia; //[insomnia] [varchar](5) null,
	    private string _ha; //[ha] [varchar](5) null,
	    private string _dizziness; //[dizziness] [varchar](5) null,
	    private string _nausea; //[nausea] [varchar](5) null,
	    private string _drymouth; //[drymouth] [varchar](5) null,
	    private string _diahrea; //[diarhhea] [varchar](5) null,
	    private string _consipation; //[consipation] [varchar](5) null,
	    private string _increasedappetite;//[increasedappetite] [varchar](5) null,
        private string _decreasedappetite;
	    private string _blurredvision; //[blurredvision] [varchar](5) null,
	    private string _sexualdysfunction; //[sexualdysfunction] [varchar](5) null,
	    private string _eps; //[eps] [varchar](5) null,
	    private string _td; //[td] [varchar](5) null,
	    private string _weightgain; //[weightgain] [varchar](5) null,
	    private string _weightloss; //[weightloss] [varchar](5) null,
	    private string _other; //[other] [varchar](50) null,
	    private string _sleep;//[sleep] [varchar](15) null,
	    private string _bedtime; //[bedtime] [varchar](15) null,
	    private string _delay;//[delay] [varchar](15) null,
	    private string _restless; //[restless] [varchar](5) null,
	    private string _ema; //[ema] [varchar](5) null,
	    private string _arisetime;//[arisetime] [varchar](15) null,
	    private string _comments2; //[comments2] [varchar](255) null,
	    private string _appetite;//[appetite] [varchar](15) null,
	    private string _energy; //[energy] [varchar](15) null,
	    private string _chronicillness; //[chronicillness] [varchar](255) null,
	    private string _acuteillness; //[acuteillness] [varchar](255) null,
	    private string _tobacco; //[tobacco] [varchar](255) null,
	    private string _drugs; //[drugs] [varchar](255) null,
	    private string _general; //[general] [varchar](255) null,
	    private string _initials; //[initials] [varchar](10) null,
	    private string _reviewed; //[reviewed] [varchar](5) null,
	    private string _reviewdate; //[reviewdate] [varchar](50) null,
	    private string _abnormalities; //[abnormalities] [varchar](255) null,
	    private string _psychoreview; //[psychoreview] [varchar](5) null,
	    private string _relationshipissues; //[relationshipissues] [varchar](20) null,
	    private string _relationshipcomments; //[relationshipcomments] [varchar](255) null,
	    private string _vocationcomments; //[vocationcomments] [varchar](255) null,
	    private string _educationalcomments; //[educationalcomments] [varchar](255) null,
	    private string _crossculturalcomments;//[crossculturalcomments] [varchar](255) null,
	    private string _calm; //[calm] [varchar](5) null,
	    private string _tense; //[tense] [varchar](5) null,
	    private string _alert; //[alert] [varchar](5) null,
	    private string _tired; //[tired] [varchar](5) null,
	    private string _slowed; //[slowed] [varchar](5) null,
	    private string _hyperactive; //[hyperactive] [varchar](5) null,
	    private string _wellrelated; //[wellrelated] [varchar](5) null,
	    private string _cooperative; //[cooperative] [varchar](5) null,
	    private string _poised; //[poised] [varchar](5) null,
	    private string _agitated; //[agitated] [varchar](5) null,
	    private string _rigid; //[rigid] [varchar](5) null,
	    private string _hostile; //[hostile] [varchar](5) null,
	    private string _tearful; //[tearful] [varchar](5) null,
	    private string _depressed; //[depressed] [varchar](5) null,
	    private string _guarded; //[guarded] [varchar](5) null,
	    private string _paranoid; //[paranoid] [varchar](5) null,
	    private string _demanding; //[demanding] [varchar](5) null,
	    private string _gestures; //[gestures] [varchar](5) null,
	    private string _tics; //[tics] [varchar](5) null,
    	private string _mentalother; //[mentalother] [varchar](55) null,
    	private string _spontaneous; //[spontaneous] [varchar](5) null,
	private string _normalrelaxed;//[normalrelaxed] [varchar](5) null,
	private string _hesitation;//[hesitation] [varchar](5) null,
	private string _pressured;//[pressured] [varchar](5) null,
	private string _monotonous;//[monotonous] [varchar](5) null,
	private string _dysarthric;//[dysarthric] [varchar](5) null,
	private string _speechother;//[speechother] [varchar](55) null,
	private string _euthymic;//[euthymic] [varchar](5) null,
	private string _despairing;//[despairing] [varchar](5) null,
	private string _mooddepressed;//[mooddepressed] [varchar](5) null,
	private string _hopeless;//hopeless] [varchar](5) null,
	private string _irritable;//[irritable] [varchar](5) null,
	private string _expansive;//[expansive] [varchar](5) null,
	private string _anxious;//[anxious] [varchar](5) null,
	private string _angry;//[angry] [varchar](5) null,
	private string _guilty;//[guilty] [varchar](5) null,
	private string _congruent;//[congruent] [varchar](5) null,
	private string _constricted;//[constricted] [varchar](5) null,
	private string _broadexpansive;//[broadexpansive] [varchar](5) null,
	private string _blunted;//[blunted] [varchar](5) null,
	private string _dysphoric;//[dysphoric] [varchar](5) null,
	private string _euphoric;//[euphoric] [varchar](5) null,
	private string _labile;//[labile] [varchar](5) null,
	private string _manic;//[manic] [varchar](5) null,
	private string _flat;//[flat] [varchar](5) null,
	private string _auditory;//[auditory] [varchar](5) null,
	private string _visual;///[visual] [varchar](5) null,
    private string _tactile;//[tactile] [varchar](5) null,
        private string _coffee;
        private string _tea;
        private string _caffeinatedsodas;
        private string _caffeinatedsodas_oz;

        private string _gustatory;//[gustatory] [varchar](5) null,

        public int Active
        {
            get { return _Active; }
            set { _Active = value; }
        }

        public string coffee {
            get { return _coffee; }
            set { _coffee = value; }
        }

        public string tea
        {
            get { return _tea; }
            set { _tea = value; }
        }

        public string caffeinatedsodas
        {
            get { return _caffeinatedsodas; }
            set { _caffeinatedsodas = value; }
        }

        public string caffeinatedsodas_oz
        {
            get { return _caffeinatedsodas_oz; }
            set { _caffeinatedsodas_oz = value; }
        }

        public string decreasedappetite
        {
            get { return _decreasedappetite; }
            set { _decreasedappetite = value; }
        }


        public Int64 medevalid
        {
            get { return _medevalid; }
            set { _medevalid = value; }
        }


        public string abnormalities
        {
            get
            {
                return _abnormalities;
            }
            set
            {
                _abnormalities = value;
            }
        }
        public string accompaniedby
        {
            get
            {
                return _accompaniedby;
            }
            set
            {
                _accompaniedby = value;
            }
        }
        public string acuteillness
        {
            get
            {
                return _acuteillness;
            }
            set
            {
                _acuteillness = value;
            }
        }
        public string age
        {
            get
            {
                return _age;
            }
            set
            {
                _age = value;
            }
        }
        public string agitated
        {
            get
            {
                return _agitated;
            }
            set
            {
                _agitated = value;
            }
        }
        public string aims
        {
            get
            {
                return _aims;
            }
            set
            {
                _aims = value;
            }
        }
        public string alert
        {
            get
            {
                return _alert;
            }
            set
            {
                _alert = value;
            }
        }
        public string angry
        {
            get
            {
                return _angry;
            }
            set
            {
                _angry = value;
            }
        }
        public string anxious
        {
            get
            {
                return _anxious;
            }
            set
            {
                _anxious = value;
            }
        }
        public string appetite
        {
            get
            {
                return _appetite;
            }
            set
            {
                _appetite = value;
            }
        }
        public string arisetime
        {
            get
            {
                return _arisetime;
            }
            set
            {
                _arisetime = value;
            }
        }
        public string auditory
        {
            get
            {
                return _auditory;
            }
            set
            {
                _auditory = value;
            }
        }
        public string bedtime
        {
            get
            {
                return _bedtime;
            }
            set
            {
                _bedtime = value;
            }
        }
        public string blunted
        {
            get
            {
                return _blunted;
            }
            set
            {
                _blunted = value;
            }
        }
        public string blurredvision
        {
            get
            {
                return _blurredvision;
            }
            set
            {
                _blurredvision = value;
            }
        }
        public string bp
        {
            get
            {
                return _bp;
            }
            set
            {
                _bp = value;
            }
        }
        public string broadexpansive
        {
            get
            {
                return _broadexpansive;
            }
            set
            {
                _broadexpansive = value;
            }
        }
        public string calm
        {
            get
            {
                return _calm;
            }
            set
            {
                _calm = value;
            }
        }
        public string chronicillness
        {
            get
            {
                return _chronicillness;
            }
            set
            {
                _chronicillness = value;
            }
        }
        public string clientcode
        {
            get
            {
                return _clientcode;
            }
            set
            {
                _clientcode = value;
            }
        }
        public string clientname
        {
            get
            {
                return _clientname;
            }
            set
            {
                _clientname = value;
            }
        }
        ///[comments] [varchar](255) null,
        public string comments
        {
            get
            {
                return _comments;
            }
            set
            {
                _comments = value;
            }
        }
        public string comments2
        {
            get
            {
                return _comments2;
            }
            set
            {
                _comments2 = value;
            }
        }
        public string congruent
        {
            get
            {
                return _congruent;
            }
            set
            {
                _congruent = value;
            }
        }
        public string consipation
        {
            get
            {
                return _consipation;
            }
            set
            {
                _consipation = value;
            }
        }
        public string constricted
        {
            get
            {
                return _constricted;
            }
            set
            {
                _constricted = value;
            }
        }
        public string cooperative
        {
            get
            {
                return _cooperative;
            }
            set
            {
                _cooperative = value;
            }
        }
        public string crossculturalcomments
        {
            get
            {
                return _crossculturalcomments;
            }
            set
            {
                _crossculturalcomments = value;
            }
        }
        public string date
        {
            get
            {
                return _date;
            }
            set
            {
                _date = value;
            }
        }
        public string delay
        {
            get
            {
                return _delay;
            }
            set
            {
                _delay = value;
            }
        }
        public string demanding
        {
            get
            {
                return _demanding;
            }
            set
            {
                _demanding = value;
            }
        }
        public string depressed
        {
            get
            {
                return _depressed;
            }
            set
            {
                _depressed = value;
            }
        }
        public string despairing
        {
            get
            {
                return _despairing;
            }
            set
            {
                _despairing = value;
            }
        }
        public string diarhea
        {
            get
            {
                return _diahrea;
            }
            set
            {
                _diahrea = value;
            }
        }
        public string dizziness
        {
            get
            {
                return _dizziness;
            }
            set
            {
                _dizziness = value;
            }
        }
        public string dose1
        {
            get
            {
                return _dose1;
            }
            set
            {
                _dose1 = value;
            }
        }
        public string dose2
        {
            get
            {
                return _dose2;
            }
            set
            {
                _dose2 = value;
            }
        }
        public string dose3
        {
            get
            {
                return _dose3;
            }
            set
            {
                _dose3 = value;
            }
        }
        public string dose4
        {
            get
            {
                return _dose4;
            }
            set
            {
                _dose4 = value;
            }
        }
        public string drugs
        {
            get
            {
                return _drugs;
            }
            set
            {
                _drugs = value;
            }
        }
        public string drymouth
        {
            get
            {
                return _drymouth;
            }
            set
            {
                _drymouth = value;
            }
        }
        public string dysarthric
        {
            get
            {
                return _dysarthric;
            }
            set
            {
                _dysarthric = value;
            }
        }
        public string dysphoric
        {
            get
            {
                return _dysphoric;
            }
            set
            {
                _dysphoric = value;
            }
        }
        public string educationalcomments
        {
            get
            {
                return _educationalcomments;
            }
            set
            {
                _educationalcomments = value;
            }
        }
        public string ema
        {
            get
            {
                return _ema;
            }
            set
            {
                _ema = value;
            }
        }
        public string enddate1
        {
            get
            {
                return _enddate1;
            }
            set
            {
                _enddate1 = value;
            }
        }
        public string enddate2
        {
            get
            {
                return _enddate2;
            }
            set
            {
                _enddate2 = value;
            }
        }
        public string enddate3
        {
            get
            {
                return _enddate3;
            }
            set
            {
                _enddate3 = value;
            }
        }
        public string enddate4
        {
            get
            {
                return 
                    _enddate4;
            }
            set
            {
                _enddate4 = value;
            }
        }
        public string energy
        {
            get
            {
                return _energy;
            }
            set
            {
                _energy = value;
            }
        }
        public string eps
        {
            get
            {
                return _eps;
            }
            set
            {
                _eps = value;
            }
        }
        public string euphoric
        {
            get
            {
                return _euphoric;
            }
            set
            {
                _euphoric = value;
            }
        }
        public string euthymic
        {
            get
            {
                return _euthymic;
            }
            set
            {
                _euthymic = value;
            }
        }
        public string expansive
        {
            get
            {
                return _expansive;
            }
            set
            {
                _expansive = value;
            }
        }
        public string flat
        {
            get
            {
                return _flat;
            }
            set
            {
                _flat = value;
            }
        }
        public string freq1
        {
            get
            {
                return _freq1;
            }
            set
            {
                _freq1 = value;
            }
        }
        public string freq2
        {
            get
            {
                return _freq2;
            }
            set
            {
                _freq2 = value;
            }
        }
        public string freq3
        {
            get
            {
                return _freq3;
            }
            set
            {
                _freq3 = value;
            }
        }
        public string freq4
        {
            get
            {
                return _freq4;
            }
            set
            {
                _freq4 = value;
            }
        }
        public string general
        {
            get
            {
                return _general;
            }
            set
            {
                _general = value;
            }
        }
        public string gestures
        {
            get
            {
                return _gestures;
            }
            set
            {
                _gestures = value;
            }
        }
        public string guarded
        {
            get
            {
                return _guarded;
            }
            set
            {
                _guarded = value;
            }
        }
        public string guilty
        {
            get
            {
                return _guilty;
            }
            set
            {
                _guilty = value;
            }
        }
        public string gustatory
        {
            get
            {
                return _gustatory;
            }
            set
            {
                _gustatory = value;
            }
        }
        public string ha
        {
            get
            {
                return _ha;
            }
            set
            {
                _ha = value;
            }
        }
        public string hesitation
        {
            get
            {
                return _hesitation;
            }
            set
            {
                _hesitation = value;
            }
        }
        public string hopeless
        {
            get
            {
                return _hopeless;
            }
            set
            {
                _hopeless = value;
            }
        }
        public string hostile
        {
            get
            {
                return _hostile;
            }
            set
            {
                _hostile = value;
            }
        }
        public string hyperactive
        {
            get
            {
                return _hyperactive;
            }
            set
            {
                _hyperactive = value;
            }
        }
        public string increasedappetite
        {
            get
            {
                return _increasedappetite;
            }
            set
            {
                _increasedappetite = value;
            }
        }
        public string informedconsent
        {
            get
            {
                return _informedconsent;
            }
            set
            {
                _informedconsent = value;
            }
        }
        public string initials
        {
            get
            {
                return _initials;
            }
            set
            {
                _initials = value;
            }
        }
        public string insomnia
        {
            get
            {
                return _insomnia;
            }
            set
            {
                _insomnia = value;
            }
        }
        public string instituationalpharmacy
        {
            get
            {
                return _instituationalpharmacy;
            }
            set
            {
                _instituationalpharmacy = value;
            }
        }
        public string irritable
        {
            get
            {
                return _irritable;
            }
            set
            {
                _irritable = value;
            }
        }
        public string jitters
        {
            get
            {
                return _jitters;
            }
            set
            {
                _jitters = value;
            }
        }
        public string labile
        {
            get
            {
                return _labile;
            }
            set
            {
                _labile = value;
            }
        }
        public string manic
        {
            get
            {
                return _manic;
            }
            set
            {
                _manic = value;
            }
        }
        public string medication1
        {
            get
            {
                return _medication;
            }
            set
            {
                _medication = value;
            }
        }
        public string medication2
        {
            get
            {
                return _medication2;
            }
            set
            {
                _medication2 = value;
            }
        }
        ///[medication3] [varchar](255) null,
        public string medication3
        {
            get
            {
                return _medication3;
            }
            set
            {
                _medication3 = value;
            }
        }
        public string medication4
        {
            get
            {
                return _medication4;
            }
            set
            {
                _medication4 = value;
            }
        }
        public string medicationevaluation
        {
            get
            {
                return _medicationevaluation;
            }
            set
            {
                _medicationevaluation = value;
            }
        }
        public string mentalother
        {
            get
            {
                return _mentalother;
            }
            set
            {
                _mentalother = value;
            }
        }
        public string monotonous
        {
            get
            {
                return _monotonous;
            }
            set
            {
                _monotonous = value;
            }
        }
        public string mooddepressed
        {
            get
            {
                return _mooddepressed;
            }
            set
            {
                _mooddepressed = value;
            }
        }
        public string nameofpharmacy
        {
            get
            {
                return _nameofpharmacy;
            }
            set
            {
                _nameofpharmacy = value;
            }
        }
        public string nausea
        {
            get
            {
                return _nausea;
            }
            set
            {
                _nausea = value;
            }
        }
        public string nervous
        {
            get
            {
                return _nervous;
            }
            set
            {
                _nervous = value;
            }
        }
        public string normalrelaxed
        {
            get
            {
                return _normalrelaxed;
            }
            set
            {
                _normalrelaxed = value;
            }
        }
        public string olfactory { get { return _olfactory; } set { _olfactory = value; } }
	
        private string _olfactory;//[olfactory] [varchar](5) null,
        public string depresonalization { get { return _depresonalization; } set { _depresonalization = value; } }
	
        private string _depresonalization;//[depresonalization] [varchar](5) null,
        public string derealization { get { return _derealization; } set { _derealization = value; } }
	
        private string _derealization;//[derealization] [varchar](5) null,
        public string loa { get { return _loa; } set { _loa = value; } }
	
        private string _loa;//[loa] [varchar](5) null,
        public string circumstantial { get { return _circumstantial; } set { _circumstantial = value; } }
	
        private string _circumstantial;//[circumstantial] [varchar](5) null,
    private string _goal;//[goal] [varchar](5) null,
	private string _directed;//[directed] [varchar](5) null,
	private string _perservation;//perservation] [varchar](5) null,
	private string _confabulation;//[confabulation] [varchar](5) null,
	private string _blocking;//[blocking] [varchar](5) null,
	private string _si;//[si] [varchar](5) null,
	private string _his;//[his] [varchar](5) null,
	private string _paranoia;//[paranoia] [varchar](5) null,
	private string _selfregerentiality;//[selfregerentiality] [varchar](5) null,
	private string _delusions;//[delusions] [varchar](55) null,
	private string _obsession;//[obsession] [varchar](55) null,
	private string _memory;//[memory] [varchar](5) null,
	private string _concentration;//[concentration] [varchar](5) null,
	private string _judgement;//[judgement] [varchar](5) null,
    private string _poorknowledge;//	[poorknowledge] [varchar](100) null,
	private string _insightnil;//[insightnil] [varchar](5) null,
	private string _insightpartial;//[insightpartial] [varchar](5) null,
	private string _insightfull;//[insightfull] [varchar](5) null,
	private string _comments3;//[comments3] [varchar](255) null,
	private string _analysisfinding;//[analysisfinding] [varchar](255) null,
	private string _cbc;//[cbc] [varchar](5) null,
	private string _compmetabolic;//[compmetabolic] [varchar](5) null,
	private string _tft;//[tft] [varchar](5) null,
	private string _ua;//[ua] [varchar](5) null,
	private string _serumamylase;//[serumamylase] [varchar](5) null,
	private string _serumlipase;//[serumlipase] [varchar](5) null,
	private string _valpratelevel;//[valpratelevel] [varchar](5) null,
	private string _carbamazepine;//[carbamazepine] [varchar](5) null,
	private string _uco3;//[uco3] //[varchar](5) null,
	private string _plansother;//[plansother] [varchar](50) null,
	private string _nochange;//[nochange] [varchar](5) null,
	private string _addbox;//[addbox] [varchar](5) null,
	private string _addwhat;//[addwhat] [varchar](50) null,
	private string _decrease;//[decrease] [varchar](5) null,
	private string _decreasewhat;//[decreasewhat] [varchar](50) null,
	private string _new;//[new] [varchar](5) null,
	private string _newwhat;//[newwhat] [varchar](50) null,
	private string _rxchange;//[rxchange] [varchar](255) null,
	private string _comments4;//[comments4] [varchar](255) null,
	private string _apptdate;//[apptdate] [varchar](20) null,
	private string _physician;//[physician] [varchar](50) null,
	private string _appttime;//[appttime] [varchar](20) null,
    public string addbox
    {
        get
        {
            return _addbox;
        }
        set
        {
            _addbox = value;
        }
    }
    public string addwhat
    {
        get
        {
            return _addwhat;
        }
        set
        {
            _addwhat = value;
        }
    }
    public string analysisfinding
    {
        get
        {
            return _analysisfinding;
        }
        set
        {
            _analysisfinding = value;
        }
    }
    public string apptdate
    {
        get
        {
            return _apptdate;
        }
        set
        {
            _apptdate = value;
        }
    }
    public string appttime
        {
            get
            {
                return _appttime;
            }
            set
            {
                _appttime = value;
            }
        }
        public string blocking
        {
            get
            {
                return _blocking;
            }
            set
            {
                _blocking = value;
            }
        }
        public string carbamazepine
        {
            get
            {
                return _carbamazepine;
            }
            set
            {
                _carbamazepine = value;
            }
        }
        public string cbc
        {
            get
            {
                return _cbc;
            }
            set
            {
                _cbc = value;
            }
        }
        public string comments3
        {
            get
            {
                return _comments3;
            }
            set
            {
                _comments3 = value;
            }
        }
        public string comments4
        {
            get
            {
                return _comments4;
            }
            set
            {
                _comments4 = value;
            }
        }
        public string compmetabolic
        {
            get
            {
                return _compmetabolic;
            }
            set
            {
                _compmetabolic = value;
            }
        }
        public string concentration
        {
            get
            {
                return _concentration;
            }
            set
            {
                _concentration = value;
            }
        }
        public string confabulation
        {
            get
            {
                return _confabulation;
            }
            set
            {
                _confabulation = value;
            }
        }
        public string decrease
        {
            get
            {
                return _decrease;
            }
            set
            {
                _decrease = value;
            }
        }
        public string decreasewhat
        {
            get
            {
                return _decreasewhat;
            }
            set
            {
                _decreasewhat = value;
            }
        }
        public string delusions
        {
            get
            {
                return _delusions;
            }
            set
            {
                _delusions = value;
            }
        }
        public string directed
        {
            get
            {
                return _directed;
            }
            set
            {
                _directed = value;
            }
        }
        public string goal
        {
            get
            {
                return _goal;
            }
            set
            {
                _goal = value;
            }
        }
        public string his
        {
            get
            {
                return _his;
            }
            set
            {
                _his = value;
            }
        }
        public string insightfull
        {
            get
            {
                return _insightfull;
            }
            set
            {
                _insightfull = value;
            }
        }
        public string insightnil
        {
            get
            {
                return _insightnil;
            }
            set
            {
                _insightnil = value;
            }
        }
        public string insightpartial
        {
            get
            {
                return _insightpartial;
            }
            set
            {
                _insightpartial = value;
            }
        }
        public string judgement
        {
            get
            {
                return _judgement;
            }
            set
            {
                _judgement = value;
            }
        }
        public string memory
        {
            get
            {
                return _memory;
            }
            set
            {
                _memory = value;
            }
        }
        
        public string New
        {
            get
            {
                return _new;
            }
            set
            {
                _new = value;
            }
        }
        
        public string newwhat
        {
            get
            {
                return _newwhat;
            }
            set
            {
                _newwhat = value;
            }
        }
        public string nochange
        {
            get
            {
                return _nochange;
            }
            set
            {
                _nochange = value;
            }
        }
        public string obsession
        {
            get
            {
                return _obsession;
            }
            set
            {
                _obsession = value;
            }
        }
        public string other
        {
            get
            {
                return _other;
            }
            set
            {
                _other = value;
            }
        }
        public string p
        {
            get
            {
                return _p;
            }
            set
            {
                _p = value;
            }
        }
        public string paranoia
        {
            get
            {
                return _paranoia;
            }
            set
            {
                _paranoia = value;
            }
        }
        public string paranoid
        {
            get
            {
                return _paranoid;
            }
            set
            {
                _paranoid = value;
            }
        }
        public string perservation
        {
            get
            {
                return _perservation;
            }
            set
            {
                _perservation = value;
            }
        }
        public string physician
        {
            get
            {
                return _physician;
            }
            set
            {
                _physician = value;
            }
        }
        public string plansother
        {
            get
            {
                return _plansother;
            }
            set
            {
                _plansother = value;
            }
        }
        public string poised
        {
            get
            {
                return _poised;
            }
            set
            {
                _poised = value;
            }
        }
        public string poorknowledge
        {
            get
            {
                return _poorknowledge;
            }
            set
            {
                _poorknowledge = value;
            }
        }
        public string pressured
        {
            get
            {
                return _pressured;
            }
            set
            {
                _pressured = value;
            }
        }
        public string psychoreview
        {
            get
            {
                return _psychoreview;
            }
            set
            {
                _psychoreview = value;
            }
        }
        public string relationshipcomments
        {
            get
            {
                return _relationshipcomments;
            }
            set
            {
                _relationshipcomments = value;
            }
        }
        public string relationshipissues
        {
            get
            {
                return _relationshipissues;
            }
            set
            {
                _relationshipissues = value;
            }
        }
        public string restless
        {
            get
            {
                return _restless;
            }
            set
            {
                _restless = value;
            }
        }
        public string reviewdate
        {
            get
            {
                return _reviewdate;
            }
            set
            {
                _reviewdate = value;
            }
        }
        public string reviewed
        {
            get
            {
                return _reviewed;
            }
            set
            {
                _reviewed = value;
            }
        }
        public string rigid
        {
            get
            {
                return _rigid;
            }
            set
            {
                _rigid = value;
            }
        }
        public string rr
        {
            get
            {
                return _rr;
            }
            set
            {
                _rr = value;
            }
        }
        public string rte1
        {
            get
            {
                return _rte1;
            }
            set
            {
                _rte1 = value;
            }
        }
        public string rte2
        {
            get
            {
                return _rte2;
            }
            set
            {
                _rte2 = value;
            }
        }
        public string rte3
        {
            get
            {
                return _rte3;
            }
            set
            {
                _rte3 = value;
            }
        }
        public string rte4
        {
            get
            {
                return _rte4;
            }
            set
            {
                _rte4 = value;
            }
        }
        public string rxchange
        {
            get
            {
                return _rxchange;
            }
            set
            {
                _rxchange = value;
            }
        }
        public string rxcompliance
        {
            get
            {
                return _rxcompliance;
            }
            set
            {
                _rxcompliance = value;
            }
        }
        public string rxreview
        {
            get
            {
                return _rxreview;
            }
            set
            {
                _rxreview = value;
            }
        }
        public string selfregerentiality
        {
            get
            {
                return _selfregerentiality;
            }
            set
            {
                _selfregerentiality = value;
            }
        }
        public string serumamylase
        {
            get
            {
                return _serumamylase;
            }
            set
            {
                _serumamylase = value;
            }
        }
        public string serumlipase
        {
            get
            {
                return _serumlipase;
            }
            set
            {
                _serumlipase = value;
            }
        }
        public string sexualdysfunction
        {
            get
            {
                return _sexualdysfunction;
            }
            set
            {
                _sexualdysfunction = value;
            }
        }
        public string si
        {
            get
            {
                return _si;
            }
            set
            {
                _si = value;
            }
        }
        public string sideeffects2
        {
            get
            {
                return _sideeeffects2;
            }
            set
            {
                _sideeeffects2 = value;
            }
        }
        public string sideeffects1
        {
            get
            {
                return _sideeffects;
            }
            set
            {
                _sideeffects = value;
            }
        }
        ///[sideeffects3] [varchar](255) null,
        public string sideeffects3
        {
            get
            {
                return _sideeffects3;
            }
            set
            {
                _sideeffects3 = value;
            }
        }
        public string sideeffects4
        {
            get
            {
                return _sideffects4;
            }
            set
            {
                _sideffects4 = value;
            }
        }
        public string sleep
        {
            get
            {
                return _sleep;
            }
            set
            {
                _sleep = value;
            }
        }
        public string slowed
        {
            get
            {
                return _slowed;
            }
            set
            {
                _slowed = value;
            }
        }
        public string somolence
        {
            get
            {
                return _somolence;
            }
            set
            {
                _somolence = value;
            }
        }
        public string speechother
        {
            get
            {
                return _speechother;
            }
            set
            {
                _speechother = value;
            }
        }
        public string spontaneous
        {
            get
            {
                return _spontaneous;
            }
            set
            {
                _spontaneous = value;
            }
        }

        public string startdate1
        {
            get
            {
                return _startdate1;
            }
            set
            {
                _startdate1 = value;
            }
        }

        public string startdate2
        {
            get
            {
                return _startdate2;
            }
            set
            {
                _startdate2 = value;
            }
        }

        public string startdate3
        {
            get
            {
                return _startdate3;
            }
            set
            {
                _startdate3 = value;
            }
        }
        public string startdate4
        {
            get
            {
                return _startdate4;
            }
            set
            {
                _startdate4 = value;
            }
        }
        public string status
        {
            get
            {
                return _status;
            }
            set
            {
                _status = value;
            }
        }
        public string tactile
        {
            get
            {
                return _tactile;
            }
            set
            {
                _tactile = value;
            }
        }
        public string targetsymptoms1
        {
            get
            {
                return _targetsymptoms1;
            }
            set
            {
                _targetsymptoms1 = value;
            }
        }
        public string targetsymptoms2
        {
            get
            {
                return _targetsymptoms2;
            }
            set
            {
                _targetsymptoms2 = value;
            }
        }
        public string targetsymptoms3
        {
            get
            {
                return _targetsymptoms3;
            }
            set
            {
                _targetsymptoms3 = value;
            }
        }
        public string targetsymptoms4
        {
            get
            {
                return _targetsymptoms4;
            }
            set
            {
                _targetsymptoms4 = value;
            }
        }
        public string td
        {
            get
            {
                return _td;
            }
            set
            {
                _td = value;
            }
        }
        public string tearful
        {
            get
            {
                return _tearful;
            }
            set
            {
                _tearful = value;
            }
        }
        public string temp
        {
            get
            {
                return _temp;
            }
            set
            {
                _temp = value;
            }
        }
        public string tense
        {
            get
            {
                return _tense;
            }
            set
            {
                _tense = value;
            }
        }
        public string tft
        {
            get
            {
                return _tft;
            }
            set
            {
                _tft = value;
            }
        }
        public string therapist
        {
            get
            {
                return _therapist;
            }
            set
            {
                _therapist = value;
            }
        }
        public string tics
        {
            get
            {
                return _tics;
            }
            set
            {
                _tics = value;
            }
        }
        public string tired
        {
            get
            {
                return _tired;
            }
            set
            {
                _tired = value;
            }
        }
        public string tobacco
        {
            get
            {
                return _tobacco;
            }
            set
            {
                _tobacco = value;
            }
        }
        public string ua
        {
            get
            {
                return _ua;
            }
            set
            {
                _ua = value;
            }
        }
        public string uco3
        {
            get
            {
                return _uco3;
            }
            set
            {
                _uco3 = value;
            }
        }
        public string valpratelevel
        {
            get
            {
                return _valpratelevel;
            }
            set
            {
                _valpratelevel = value;
            }
        }
        ///[visual] [varchar](5) null,
        public string visual
        {
            get
            {
                return _visual;
            }
            set
            {
                _visual = value;
            }
        }
        public string vocationcomments
        {
            get
            {
                return _vocationcomments;
            }
            set
            {
                _vocationcomments = value;
            }
        }
        public string weight
        {
            get
            {
                return _weight;
            }
            set
            {
                _weight = value;
            }
        }
        public string weightgain
        {
            get
            {
                return _weightgain;
            }
            set
            {
                _weightgain = value;
            }
        }
        public string weightloss
        {
            get
            {
                return _weightloss;
            }
            set
            {
                _weightloss = value;
            }
        }
        public string wellrelated
        {
            get
            {
                return _wellrelated;
            }
            set
            {
                _wellrelated = value;
            }
        }
	
    }
}
