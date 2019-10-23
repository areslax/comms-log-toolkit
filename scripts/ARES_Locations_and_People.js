/* ############################
 * ARES_Locations_and_People.js
 * List of deployment locations
 * and ARESLAX scribes
 * Auto-updated: 2019-10-23
 * ############################ */

var loclookup = new Array(
{label:'WMH Adventist Health White Memorial - Los Angeles',value:'WMH',lid:'1'},{label:'ACH Alhambra Hospital Medical Center',value:'ACH',lid:'2'},{label:'ARCP American Recovery Center - Pomona',value:'ARCP',lid:'3'},{label:'AVH Antelope Valley Hospital - Lancaster',value:'AVH',lid:'4'},{label:'APHC Asian Pacific Health Care Venture - Hollywood',value:'APHC',lid:'5'},{label:'ACOC Aurora Charter Oak - Covina',value:'ACOC',lid:'6'},{label:'ALEH Aurora Las Encinas Hospital - Pasadena',value:'ALEH',lid:'7'},{label:'BRSP Barlow Respiratory Hospital - Los Angeles',value:'BRSP',lid:'8'},{label:'BEV Beverly Hospital - Montebello',value:'BEV',lid:'9'},{label:'BHCA BHC Alhambra Hospital',value:'BHCA',lid:'10'},{label:'CAL California Hospital Medical Center - Los Angeles',value:'CAL',lid:'11'},{label:'CRIN California Rehabilitation Institute, LLC - Los Angeles',value:'CRIN',lid:'12'},{label:'CCHP Casa Colina Hospital - Pomona',value:'CCHP',lid:'13'},{label:'AHM Catalina Island Medical Center - Avalon',value:'AHM',lid:'14'},{label:'CSM Cedars Sinai Medical Center - Los Angeles',value:'CSM',lid:'15'},{label:'DFM Cedars-Sinai Marina Del Rey Hospital',value:'DFM',lid:'16'},{label:'CNT Centinella Hospital Medical Center - Inglewood',value:'CNT',lid:'17'},{label:'CHH Children\'s Hospital Of Los Angeles',value:'CHH',lid:'18'},{label:'CHHH City Of Hope Helford Clinical Research Hospital Duarte',value:'CHHH',lid:'19'},{label:'CPM Coast Plaza Hospital - Norwalk',value:'CPM',lid:'20'},{label:'COLH College Hospital - Cerritos',value:'COLH',lid:'21'},{label:'CHLB Community Hospital Long Beach',value:'CHLB',lid:'22'},{label:'CHP Community Hospital Of Huntington Park',value:'CHP',lid:'23'},{label:'DAHT Del Amo Hospital - Torrance',value:'DAHT',lid:'24'},{label:'DOSH Department Of State Hospital - Metropolitan - Norwalk',value:'DOSH',lid:'25'},{label:'ELA East Los Angeles Doctors Hospital',value:'ELA',lid:'26'},{label:'FPH Emanate Health Foothill Presbyterian Hospital - Glendora',value:'FPH',lid:'27'},{label:'ICH Emanate Health Inter-Community Hospital - Covina',value:'ICH',lid:'28'},{label:'QVH Emanate Health Queen of the Valley Hospital - West Covina',value:'QVH',lid:'29'},{label:'ENH Encino Hospital Medical Center',value:'ENH',lid:'30'},{label:'EXRP Exodus Recovery PHF - Culver City',value:'EXRP',lid:'31'},{label:'HGRH Gardens Regional Hospital And Medical Center Hawaiian Gardens',value:'HGRH',lid:'32'},{label:'GAR Garfield Medical Center - Monterey Park',value:'GAR',lid:'33'},{label:'GHMH Gateways Hospital And Mental Health Center - Los Angeles',value:'GHMH',lid:'34'},{label:'GWT Glendale Adventist Medical Center- Glendale',value:'GWT',lid:'35'},{label:'GMH Glendale Memorial Hospital And Health Center',value:'GMH',lid:'36'},{label:'HEV Glendora Community Hospital',value:'HEV',lid:'37'},{label:'GSH Good Samaritan Hospital - Los Angeles',value:'GSH',lid:'38'},{label:'GEM Greater El Monte Community Hospital',value:'GEM',lid:'39'},{label:'HMN Henry Mayo Newhall Hospital',value:'HMN',lid:'40'},{label:'QOA Hollywood Presbyterian Medical Center',value:'QOA',lid:'41'},{label:'HMH Huntington Memorial Hospital - Pasadena',value:'HMH',lid:'42'},{label:'JEKM Joyce Eisenberg Keefer Medical Center - Reseda',value:'JEKM',lid:'43'},{label:'KFA Kaiser Foundation Hospital - Baldwin Park',value:'KFA',lid:'44'},{label:'KFB Kaiser Foundation Hospital - Downey',value:'KFB',lid:'45'},{label:'KFL Kaiser Foundation Hospital - Los Angeles',value:'KFL',lid:'46'},{label:'KFMH Kaiser Foundation Hospital - Mental Health Center - Los Angeles',value:'KFMH',lid:'47'},{label:'KFP Kaiser Foundation Hospital - Panorama City',value:'KFP',lid:'48'},{label:'KFH Kaiser Foundation Hospital - South Bay',value:'KFH',lid:'49'},{label:'KFW Kaiser Foundation Hospital - West LA',value:'KFW',lid:'50'},{label:'KFO Kaiser Foundation Hospital - Woodland Hills',value:'KFO',lid:'51'},{label:'KUSC Keck Hospital Of USC - Los Angeles',value:'KUSC',lid:'52'},{label:'KCMH Kedren Community Mental Health Center - Los Angeles',value:'KCMH',lid:'53'},{label:'KHBP Kindred Hospital - Baldwin Park',value:'KHBP',lid:'54'},{label:'KHLM Kindred Hospital - La Mirada',value:'KHLM',lid:'55'},{label:'KHLA Kindred Hospital - Los Angeles',value:'KHLA',lid:'56'},{label:'KHSG Kindred Hospital - San Gabriel Valley',value:'KHSG',lid:'57'},{label:'KHSB Kindred Hospital - South Bay',value:'KHSB',lid:'58'},{label:'LCPH La Casa Psychiatric Health Facility - Long Beach',value:'LCPH',lid:'59'},{label:'USC LAC+USC Medical Center - Los Angeles',value:'USC',lid:'62'},{label:'HGH LAC/Harbor-UCLA Medical Center - Torrance',value:'HGH',lid:'60'},{label:'LANR LAC/Rancho Los Amigos National Rehab Center - Downey',value:'LANR',lid:'61'},{label:'DHL Lakewood Regional Medical Center',value:'DHL',lid:'63'},{label:'LACH Los Angeles Community Hospital',value:'LACH',lid:'64'},{label:'LACB Los Angeles Community Hospital At Bellflower',value:'LACB',lid:'65'},{label:'OVM Los Angeles County Olive View - UCLA Medical Center - Sylmar',value:'OVM',lid:'66'},{label:'MLK Martin Luther King, Jr. Community Hospital - Los Angeles',value:'MLK',lid:'67'},{label:'MHG Memorial Hospital Of Gardena',value:'MHG',lid:'68'},{label:'LBM Memorialcare Long Beach Medical Center',value:'LBM',lid:'69'},{label:'MMCW Memorialcare Miller Children\'s & Women\'s Hospital Long Beach',value:'MMCW',lid:'70'},{label:'AMH Methodist Hospital Of Southern California - Arcadia',value:'AMH',lid:'71'},{label:'MMMC Miracle Mile Medical Center - Los Angeles',value:'MMMC',lid:'72'},{label:'MCP Mission Community Hospital - Panorama Campus',value:'MCP',lid:'73'},{label:'MVMH Monrovia Memorial Hospital',value:'MVMH',lid:'74'},{label:'MPH Monterey Park Hospital',value:'MPH',lid:'75'},{label:'MPTH Motion Picture And Television Hospital - Woodland Hills',value:'MPTH',lid:'76'},{label:'NRH Northridge Hospital Medical Center',value:'NRH',lid:'77'},{label:'NOR Norwalk Community Hospital',value:'NOR',lid:'78'},{label:'OVPH Ocean View Psychiatric Health Facility - Long Beach',value:'OVPH',lid:'79'},{label:'MID Olympia Medical Center - Los Angeles',value:'MID',lid:'80'},{label:'PLB Pacific Hospital of Long Beach',value:'PLB',lid:'81'},{label:'PAC Pacifica Hospital Of The Valley - Sun Valley',value:'PAC',lid:'82'},{label:'LCH Palmdale Regional Medical Center',value:'LCH',lid:'83'},{label:'DCH PIH Health Hospital - Downey',value:'DCH',lid:'84'},{label:'PIH PIH Health Hospital - Whittier',value:'PIH',lid:'85'},{label:'PVC Pomona Valley Hospital Medical Center',value:'PVC',lid:'86'},{label:'PELA Promise Hospital Of East Los Angeles - Suburban Campus - Paramount',value:'PELA',lid:'87'},{label:'HCH Providence Holy Cross Medical Center - Mission Hills',value:'HCH',lid:'88'},{label:'SPP Providence Little Company Of Mary MC - San Pedro',value:'SPP',lid:'89'},{label:'LCM Providence Little Company Of Mary Medical Center Torrance',value:'LCM',lid:'90'},{label:'SJH Providence Saint John\'s Health Center - Santa Monica',value:'SJH',lid:'91'},{label:'SJS Providence Saint Joseph Medical Center - Burbank',value:'SJS',lid:'92'},{label:'TRM Providence Tarzana Medical Center',value:'TRM',lid:'93'},{label:'RNLA Resnick Neuropsychiatric Hospital At UCLA - Los Angeles',value:'RNLA',lid:'94'},{label:'UCL Ronald Reagan UCLA Medical Center - Los Angeles',value:'UCL',lid:'95'},{label:'SDC San Dimas Community Hospital',value:'SDC',lid:'96'},{label:'SGC San Gabriel Valley Medical Center',value:'SGC',lid:'97'},{label:'SMH Santa Monica - UCLA Medical Center And Orthopaedic Hospital',value:'SMH',lid:'98'},{label:'SOC Sherman Oaks Hospital',value:'SOC',lid:'99'},{label:'SLMC Silver Lake Medical Center - Downtown Campus',value:'SLMC',lid:'100'},{label:'BMC Southern California Hospital at Culver City',value:'BMC',lid:'101'},{label:'SCHH Southern California Hospital at Hollywood',value:'SCHH',lid:'102'},{label:'SFM St. Francis Medical Center- Lynwood',value:'SFM',lid:'103'},{label:'SMM St. Mary Medical Center - Long Beach',value:'SMM',lid:'104'},{label:'SVH St. Vincent Medical Center - Los Angeles',value:'SVH',lid:'105'},{label:'SVAP Star View Adolescent - PHF - Torrance',value:'SVAP',lid:'106'},{label:'TZTC Tarzana Treatment Center',value:'TZTC',lid:'107'},{label:'TRRC Tom Redgate Memorial Recovery Center - Long Beach',value:'TRRC',lid:'108'},{label:'TOR Torrance Memorial Medical Center',value:'TOR',lid:'109'},{label:'KNCH USC Kenneth Norris, Jr. Cancer Hospital - Los Angeles',value:'KNCH',lid:'110'},{label:'VHH USC Verdugo Hills Hospital - Glendale',value:'VHH',lid:'111'},{label:'VPH Valley Presbyterian Hospital - Van Nuys',value:'VPH',lid:'112'},{label:'WCMC West Covina Medical Center',value:'WCMC',lid:'113'},{label:'HWH West Hills Hospital And Medical Center',value:'HWH',lid:'114'},{label:'WHH Whittier Hospital Medical Center',value:'WHH',lid:'115'}
);

var people = new Array(
{label:'AJ6DL Darren La Groe',value:'AJ6DL Darren La Groe',mid:'9'},{label:'K6AUR Alexander Auerbach',value:'K6AUR Alexander Auerbach',mid:'26'},{label:'K6OAT Dan Ruderman',value:'K6OAT Dan Ruderman',mid:'10'},{label:'K9SDS Stephen Smedberg',value:'K9SDS Stephen Smedberg',mid:'19'},{label:'KJ6UVQ Dennis Payne',value:'KJ6UVQ Dennis Payne',mid:'7'},{label:'KK6DA David Ahrendts',value:'KK6DA David Ahrendts',mid:'5'},{label:'KM6JWV Martin Rumpf',value:'KM6JWV Martin Rumpf',mid:'27'},{label:'KM6LFE Jessica Lee',value:'KM6LFE Jessica Lee',mid:'28'},{label:'KM6RWG Scott Spooner',value:'KM6RWG Scott Spooner',mid:'18'},{label:'KM6WKA James Butler',value:'KM6WKA James Butler',mid:'1'},{label:'N4XRO David Poole',value:'N4XRO David Poole',mid:'17'},{label:'NR6V Dan Tomlinson',value:'NR6V Dan Tomlinson',mid:'11'},{label:'W0DHG David Goldenberg',value:'W0DHG David Goldenberg',mid:'8'},{label:'W1EH Roozy Moabery',value:'W1EH Roozy Moabery',mid:'4'},{label:'W6AH Chris Mattia',value:'W6AH Chris Mattia',mid:'12'},{label:'WA6P Dean Cuadra',value:'WA6P Dean Cuadra',mid:'6'}
);

var incidents = new Array(

);

var netcontrols = new Array(

);

var typs = new Array(
'',
'MCI Poll',
'HSA Poll',
'Event',
'Resource Request',
'Relay Request',
'Action',
'Report'
);


