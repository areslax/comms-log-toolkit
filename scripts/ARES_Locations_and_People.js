/* ############################
 * ARES_Locations_and_People.js
 * List of deployment locations
 * and ARESLAX scribes
 * Auto-updated: 2019-11-20
 * ############################ */

var locs = new Array(
{label:'77PS 77th Community Police Station',value:'77PS',lid:'129'},{label:'WMH Adventist Health White Memorial - Los Angeles',value:'WMH',lid:'1'},{label:'ACH Alhambra Hospital Medical Center',value:'ACH',lid:'2'},{label:'ARCP American Recovery Center - Pomona',value:'ARCP',lid:'3'},{label:'AVH Antelope Valley Hospital - Lancaster',value:'AVH',lid:'4'},{label:'APHC Asian Pacific Health Care Venture - Hollywood',value:'APHC',lid:'5'},{label:'ACOC Aurora Charter Oak - Covina',value:'ACOC',lid:'6'},{label:'ALEH Aurora Las Encinas Hospital - Pasadena',value:'ALEH',lid:'7'},{label:'BRSP Barlow Respiratory Hospital - Los Angeles',value:'BRSP',lid:'8'},{label:'BEV Beverly Hospital - Montebello',value:'BEV',lid:'9'},{label:'BHCA BHC Alhambra Hospital',value:'BHCA',lid:'10'},{label:'CAL California Hospital Medical Center - Los Angeles',value:'CAL',lid:'11'},{label:'CRIN California Rehabilitation Institute, LLC - Los Angeles',value:'CRIN',lid:'12'},{label:'CCHP Casa Colina Hospital - Pomona',value:'CCHP',lid:'13'},{label:'AHM Catalina Island Medical Center - Avalon',value:'AHM',lid:'14'},{label:'CSM Cedars Sinai Medical Center - Los Angeles',value:'CSM',lid:'15'},{label:'DFM Cedars-Sinai Marina Del Rey Hospital',value:'DFM',lid:'16'},{label:'CNT Centinella Hospital Medical Center - Inglewood',value:'CNT',lid:'17'},{label:'CPS Central Community Police Station ',value:'CPS',lid:'116'},{label:'CHH Children\'s Hospital Of Los Angeles',value:'CHH',lid:'18'},{label:'CHHH City Of Hope Helford Clinical Research Hospital Duarte',value:'CHHH',lid:'19'},{label:'CPM Coast Plaza Hospital - Norwalk',value:'CPM',lid:'20'},{label:'COLH College Hospital - Cerritos',value:'COLH',lid:'21'},{label:'CHLB Community Hospital Long Beach',value:'CHLB',lid:'22'},{label:'CHP Community Hospital Of Huntington Park',value:'CHP',lid:'23'},{label:'DAHT Del Amo Hospital - Torrance',value:'DAHT',lid:'24'},{label:'DOSH Department Of State Hospital - Metropolitan - Norwalk',value:'DOSH',lid:'25'},{label:'DPS Devonshire Community Police Station',value:'DPS',lid:'117'},{label:'ELA East Los Angeles Doctors Hospital',value:'ELA',lid:'26'},{label:'FPH Emanate Health Foothill Presbyterian Hospital - Glendora',value:'FPH',lid:'27'},{label:'ICH Emanate Health Inter-Community Hospital - Covina',value:'ICH',lid:'28'},{label:'QVH Emanate Health Queen of the Valley Hospital - West Covina',value:'QVH',lid:'29'},{label:'ENH Encino Hospital Medical Center',value:'ENH',lid:'30'},{label:'EXRP Exodus Recovery PHF - Culver City',value:'EXRP',lid:'31'},{label:'FPS Foothill Community Police Station',value:'FPS',lid:'118'},{label:'HGRH Gardens Regional Hospital And Medical Center Hawaiian Gardens',value:'HGRH',lid:'32'},{label:'GAR Garfield Medical Center - Monterey Park',value:'GAR',lid:'33'},{label:'GHMH Gateways Hospital And Mental Health Center - Los Angeles',value:'GHMH',lid:'34'},{label:'GWT Glendale Adventist Medical Center- Glendale',value:'GWT',lid:'35'},{label:'GMH Glendale Memorial Hospital And Health Center',value:'GMH',lid:'36'},{label:'HEV Glendora Community Hospital',value:'HEV',lid:'37'},{label:'GSH Good Samaritan Hospital - Los Angeles',value:'GSH',lid:'38'},{label:'GEM Greater El Monte Community Hospital',value:'GEM',lid:'39'},{label:'HPS Harbor Community Police Station ',value:'HPS',lid:'119'},{label:'HMN Henry Mayo Newhall Hospital',value:'HMN',lid:'40'},{label:'HBPS Hollenbeck Community Police Station ',value:'HBPS',lid:'120'},{label:'HWPS Hollywood Community Police Station',value:'HWPS',lid:'121'},{label:'QOA Hollywood Presbyterian Medical Center',value:'QOA',lid:'41'},{label:'HMH Huntington Memorial Hospital - Pasadena',value:'HMH',lid:'42'},{label:'JEKM Joyce Eisenberg Keefer Medical Center - Reseda',value:'JEKM',lid:'43'},{label:'KFA Kaiser Foundation Hospital - Baldwin Park',value:'KFA',lid:'44'},{label:'KFB Kaiser Foundation Hospital - Downey',value:'KFB',lid:'45'},{label:'KFL Kaiser Foundation Hospital - Los Angeles',value:'KFL',lid:'46'},{label:'KFMH Kaiser Foundation Hospital - Mental Health Center - Los Angeles',value:'KFMH',lid:'47'},{label:'KFP Kaiser Foundation Hospital - Panorama City',value:'KFP',lid:'48'},{label:'KFH Kaiser Foundation Hospital - South Bay',value:'KFH',lid:'49'},{label:'KFW Kaiser Foundation Hospital - West LA',value:'KFW',lid:'50'},{label:'KFO Kaiser Foundation Hospital - Woodland Hills',value:'KFO',lid:'51'},{label:'KUSC Keck Hospital Of USC - Los Angeles',value:'KUSC',lid:'52'},{label:'KCMH Kedren Community Mental Health Center - Los Angeles',value:'KCMH',lid:'53'},{label:'KHBP Kindred Hospital - Baldwin Park',value:'KHBP',lid:'54'},{label:'KHLM Kindred Hospital - La Mirada',value:'KHLM',lid:'55'},{label:'KHLA Kindred Hospital - Los Angeles',value:'KHLA',lid:'56'},{label:'KHSG Kindred Hospital - San Gabriel Valley',value:'KHSG',lid:'57'},{label:'KHSB Kindred Hospital - South Bay',value:'KHSB',lid:'58'},{label:'LCPH La Casa Psychiatric Health Facility - Long Beach',value:'LCPH',lid:'59'},{label:'USC LAC+USC Medical Center - Los Angeles',value:'USC',lid:'62'},{label:'HGH LAC/Harbor-UCLA Medical Center - Torrance',value:'HGH',lid:'60'},{label:'LANR LAC/Rancho Los Amigos National Rehab Center - Downey',value:'LANR',lid:'61'},{label:'LAFD 1 LAFD Station 1',value:'LAFD 1',lid:'193'},{label:'LAFD 10 LAFD Station 10',value:'LAFD 10',lid:'160'},{label:'LAFD 100 LAFD Station 100',value:'LAFD 100',lid:'229'},{label:'LAFD 101 LAFD Station 101',value:'LAFD 101',lid:'162'},{label:'LAFD 102 LAFD Station 102',value:'LAFD 102',lid:'157'},{label:'LAFD 103 LAFD Station 103',value:'LAFD 103',lid:'180'},{label:'LAFD 104 LAFD Station 104',value:'LAFD 104',lid:'238'},{label:'LAFD 105 LAFD Station 105',value:'LAFD 105',lid:'227'},{label:'LAFD 106 LAFD Station 106',value:'LAFD 106',lid:'194'},{label:'LAFD 107 LAFD Station 107',value:'LAFD 107',lid:'189'},{label:'LAFD 108 LAFD Station 108',value:'LAFD 108',lid:'155'},{label:'LAFD 109 LAFD Station 109',value:'LAFD 109',lid:'175'},{label:'LAFD 11 LAFD Station 11',value:'LAFD 11',lid:'181'},{label:'LAFD 110 LAFD Station 110',value:'LAFD 110',lid:'198'},{label:'LAFD 111 LAFD Station 111',value:'LAFD 111',lid:'242'},{label:'LAFD 112 LAFD Station 112',value:'LAFD 112',lid:'213'},{label:'LAFD 114 LAFD Station 114',value:'LAFD 114',lid:'176'},{label:'LAFD 12 LAFD Station 12',value:'LAFD 12',lid:'226'},{label:'LAFD 13 LAFD Station 13',value:'LAFD 13',lid:'195'},{label:'LAFD 14 LAFD Station 14',value:'LAFD 14',lid:'204'},{label:'LAFD 15 LAFD Station 15',value:'LAFD 15',lid:'199'},{label:'LAFD 16 LAFD Station 16',value:'LAFD 16',lid:'187'},{label:'LAFD 17 LAFD Station 17',value:'LAFD 17',lid:'174'},{label:'LAFD 18 LAFD Station 18',value:'LAFD 18',lid:'152'},{label:'LAFD 19 LAFD Station 19',value:'LAFD 19',lid:'153'},{label:'LAFD 2 LAFD Station 2',value:'LAFD 2',lid:'185'},{label:'LAFD 20 LAFD Station 20',value:'LAFD 20',lid:'191'},{label:'LAFD 21 LAFD Station 21',value:'LAFD 21',lid:'150'},{label:'LAFD 23 LAFD Station 23',value:'LAFD 23',lid:'177'},{label:'LAFD 24 LAFD Station 24',value:'LAFD 24',lid:'241'},{label:'LAFD 25 LAFD Station 25',value:'LAFD 25',lid:'197'},{label:'LAFD 26 LAFD Station 26',value:'LAFD 26',lid:'186'},{label:'LAFD 27 LAFD Station 27',value:'LAFD 27',lid:'158'},{label:'LAFD 28 LAFD Station 28',value:'LAFD 28',lid:'149'},{label:'LAFD 29 LAFD Station 29',value:'LAFD 29',lid:'208'},{label:'LAFD 3 LAFD Station 3',value:'LAFD 3',lid:'144'},{label:'LAFD 33 LAFD Station 33',value:'LAFD 33',lid:'228'},{label:'LAFD 34 LAFD Station 34',value:'LAFD 34',lid:'205'},{label:'LAFD 35 LAFD Station 35',value:'LAFD 35',lid:'172'},{label:'LAFD 36 LAFD Station 36',value:'LAFD 36',lid:'139'},{label:'LAFD 37 LAFD Station 37',value:'LAFD 37',lid:'146'},{label:'LAFD 38 LAFD Station 38',value:'LAFD 38',lid:'154'},{label:'LAFD 39 LAFD Station 39',value:'LAFD 39',lid:'166'},{label:'LAFD 4 LAFD Station 4',value:'LAFD 4',lid:'216'},{label:'LAFD 40 LAFD Station 40',value:'LAFD 40',lid:'203'},{label:'LAFD 41 LAFD Station 41',value:'LAFD 41',lid:'165'},{label:'LAFD 42 LAFD Station 42',value:'LAFD 42',lid:'188'},{label:'LAFD 43 LAFD Station 43',value:'LAFD 43',lid:'206'},{label:'LAFD 44 LAFD Station 44',value:'LAFD 44',lid:'161'},{label:'LAFD 46 LAFD Station 46',value:'LAFD 46',lid:'212'},{label:'LAFD 47 LAFD Station 47',value:'LAFD 47',lid:'217'},{label:'LAFD 48 LAFD Station 48',value:'LAFD 48',lid:'173'},{label:'LAFD 49 LAFD Station 49',value:'LAFD 49',lid:'207'},{label:'LAFD 5 LAFD Station 5',value:'LAFD 5',lid:'239'},{label:'LAFD 50 LAFD Station 50',value:'LAFD 50',lid:'200'},{label:'LAFD 51 LAFD Station 51',value:'LAFD 51',lid:'141'},{label:'LAFD 52 LAFD Station 52',value:'LAFD 52',lid:'218'},{label:'LAFD 55 LAFD Station 55',value:'LAFD 55',lid:'214'},{label:'LAFD 56 LAFD Station 56',value:'LAFD 56',lid:'196'},{label:'LAFD 57 LAFD Station 57',value:'LAFD 57',lid:'235'},{label:'LAFD 58 LAFD Station 58',value:'LAFD 58',lid:'171'},{label:'LAFD 59 LAFD Station 59',value:'LAFD 59',lid:'148'},{label:'LAFD 6 LAFD Station 6',value:'LAFD 6',lid:'202'},{label:'LAFD 60 LAFD Station 60',value:'LAFD 60',lid:'222'},{label:'LAFD 61 LAFD Station 61',value:'LAFD 61',lid:'225'},{label:'LAFD 62 LAFD Station 62',value:'LAFD 62',lid:'151'},{label:'LAFD 63 LAFD Station 63',value:'LAFD 63',lid:'184'},{label:'LAFD 64 LAFD Station 64',value:'LAFD 64',lid:'145'},{label:'LAFD 65 LAFD Station 65',value:'LAFD 65',lid:'178'},{label:'LAFD 66 LAFD Station 66',value:'LAFD 66',lid:'183'},{label:'LAFD 67 LAFD Station 67',value:'LAFD 67',lid:'223'},{label:'LAFD 68 LAFD Station 68',value:'LAFD 68',lid:'220'},{label:'LAFD 69 LAFD Station 69',value:'LAFD 69',lid:'169'},{label:'LAFD 7 LAFD Station 7',value:'LAFD 7',lid:'168'},{label:'LAFD 70 LAFD Station 70',value:'LAFD 70',lid:'243'},{label:'LAFD 71 LAFD Station 71',value:'LAFD 71',lid:'143'},{label:'LAFD 72 LAFD Station 72',value:'LAFD 72',lid:'230'},{label:'LAFD 73 LAFD Station 73',value:'LAFD 73',lid:'233'},{label:'LAFD 74 LAFD Station 74',value:'LAFD 74',lid:'234'},{label:'LAFD 75 LAFD Station 75',value:'LAFD 75',lid:'170'},{label:'LAFD 76 LAFD Station 76',value:'LAFD 76',lid:'201'},{label:'LAFD 77 LAFD Station 77',value:'LAFD 77',lid:'240'},{label:'LAFD 78 LAFD Station 78',value:'LAFD 78',lid:'209'},{label:'LAFD 79 LAFD Station 79',value:'LAFD 79',lid:'179'},{label:'LAFD 8 LAFD Station 8',value:'LAFD 8',lid:'147'},{label:'LAFD 80 LAFD Station 80',value:'LAFD 80',lid:'232'},{label:'LAFD 81 LAFD Station 81',value:'LAFD 81',lid:'164'},{label:'LAFD 82 LAFD Station 82',value:'LAFD 82',lid:'224'},{label:'LAFD 83 LAFD Station 83',value:'LAFD 83',lid:'219'},{label:'LAFD 84 LAFD Station 84',value:'LAFD 84',lid:'190'},{label:'LAFD 85 LAFD Station 85',value:'LAFD 85',lid:'159'},{label:'LAFD 86 LAFD Station 86',value:'LAFD 86',lid:'211'},{label:'LAFD 87 LAFD Station 87',value:'LAFD 87',lid:'140'},{label:'LAFD 88 LAFD Station 88',value:'LAFD 88',lid:'221'},{label:'LAFD 89 LAFD Station 89',value:'LAFD 89',lid:'231'},{label:'LAFD 9 LAFD Station 9',value:'LAFD 9',lid:'210'},{label:'LAFD 90 LAFD Station 90',value:'LAFD 90',lid:'236'},{label:'LAFD 91 LAFD Station 91',value:'LAFD 91',lid:'167'},{label:'LAFD 92 LAFD Station 92',value:'LAFD 92',lid:'142'},{label:'LAFD 93 LAFD Station 93',value:'LAFD 93',lid:'182'},{label:'LAFD 94 LAFD Station 94',value:'LAFD 94',lid:'215'},{label:'LAFD 95 LAFD Station 95',value:'LAFD 95',lid:'138'},{label:'LAFD 96 LAFD Station 96',value:'LAFD 96',lid:'192'},{label:'LAFD 97 LAFD Station 97',value:'LAFD 97',lid:'237'},{label:'LAFD 98 LAFD Station 98',value:'LAFD 98',lid:'156'},{label:'LAFD 99 LAFD Station 99',value:'LAFD 99',lid:'163'},{label:'DHL Lakewood Regional Medical Center',value:'DHL',lid:'63'},{label:'LACH Los Angeles Community Hospital',value:'LACH',lid:'64'},{label:'LACB Los Angeles Community Hospital At Bellflower',value:'LACB',lid:'65'},{label:'OVM Los Angeles County Olive View - UCLA Medical Center - Sylmar',value:'OVM',lid:'66'},{label:'MLK Martin Luther King, Jr. Community Hospital - Los Angeles',value:'MLK',lid:'67'},{label:'MHG Memorial Hospital Of Gardena',value:'MHG',lid:'68'},{label:'LBM Memorialcare Long Beach Medical Center',value:'LBM',lid:'69'},{label:'MMCW Memorialcare Miller Children\'s & Women\'s Hospital Long Beach',value:'MMCW',lid:'70'},{label:'AMH Methodist Hospital Of Southern California - Arcadia',value:'AMH',lid:'71'},{label:'MMMC Miracle Mile Medical Center - Los Angeles',value:'MMMC',lid:'72'},{label:'MCP Mission Community Hospital - Panorama Campus',value:'MCP',lid:'73'},{label:'MPS Mission Community Police Station ',value:'MPS',lid:'122'},{label:'MVMH Monrovia Memorial Hospital',value:'MVMH',lid:'74'},{label:'MPH Monterey Park Hospital',value:'MPH',lid:'75'},{label:'MPTH Motion Picture And Television Hospital - Woodland Hills',value:'MPTH',lid:'76'},{label:'NPS Newton Community Police Station ',value:'NPS',lid:'123'},{label:'NHPS North Hollywood Community Police Station',value:'NHPS',lid:'124'},{label:'NEPS Northeast Community Police Station ',value:'NEPS',lid:'125'},{label:'NRH Northridge Hospital Medical Center',value:'NRH',lid:'77'},{label:'NOR Norwalk Community Hospital',value:'NOR',lid:'78'},{label:'OVPH Ocean View Psychiatric Health Facility - Long Beach',value:'OVPH',lid:'79'},{label:'MID Olympia Medical Center - Los Angeles',value:'MID',lid:'80'},{label:'OPS Olympic Community Police Station',value:'OPS',lid:'126'},{label:'PPS Pacific Community Police Station',value:'PPS',lid:'127'},{label:'PLB Pacific Hospital of Long Beach',value:'PLB',lid:'81'},{label:'PAC Pacifica Hospital Of The Valley - Sun Valley',value:'PAC',lid:'82'},{label:'LCH Palmdale Regional Medical Center',value:'LCH',lid:'83'},{label:'DCH PIH Health Hospital - Downey',value:'DCH',lid:'84'},{label:'PIH PIH Health Hospital - Whittier',value:'PIH',lid:'85'},{label:'PVC Pomona Valley Hospital Medical Center',value:'PVC',lid:'86'},{label:'PELA Promise Hospital Of East Los Angeles - Suburban Campus - Paramount',value:'PELA',lid:'87'},{label:'HCH Providence Holy Cross Medical Center - Mission Hills',value:'HCH',lid:'88'},{label:'SPP Providence Little Company Of Mary MC - San Pedro',value:'SPP',lid:'89'},{label:'LCM Providence Little Company Of Mary Medical Center Torrance',value:'LCM',lid:'90'},{label:'SJH Providence Saint John\'s Health Center - Santa Monica',value:'SJH',lid:'91'},{label:'SJS Providence Saint Joseph Medical Center - Burbank',value:'SJS',lid:'92'},{label:'TRM Providence Tarzana Medical Center',value:'TRM',lid:'93'},{label:'RPS Rampart Community Police Station ',value:'RPS',lid:'128'},{label:'RNLA Resnick Neuropsychiatric Hospital At UCLA - Los Angeles',value:'RNLA',lid:'94'},{label:'UCL Ronald Reagan UCLA Medical Center - Los Angeles',value:'UCL',lid:'95'},{label:'SDC San Dimas Community Hospital',value:'SDC',lid:'96'},{label:'SGC San Gabriel Valley Medical Center',value:'SGC',lid:'97'},{label:'SMH Santa Monica - UCLA Medical Center And Orthopaedic Hospital',value:'SMH',lid:'98'},{label:'SOC Sherman Oaks Hospital',value:'SOC',lid:'99'},{label:'SLMC Silver Lake Medical Center - Downtown Campus',value:'SLMC',lid:'100'},{label:'SEPS Southeast Community Police Station',value:'SEPS',lid:'130'},{label:'BMC Southern California Hospital at Culver City',value:'BMC',lid:'101'},{label:'SCHH Southern California Hospital at Hollywood',value:'SCHH',lid:'102'},{label:'SWPS Southwest Community Police Station',value:'SWPS',lid:'131'},{label:'SFM St. Francis Medical Center- Lynwood',value:'SFM',lid:'103'},{label:'SMM St. Mary Medical Center - Long Beach',value:'SMM',lid:'104'},{label:'SVH St. Vincent Medical Center - Los Angeles',value:'SVH',lid:'105'},{label:'SVAP Star View Adolescent - PHF - Torrance',value:'SVAP',lid:'106'},{label:'TZTC Tarzana Treatment Center',value:'TZTC',lid:'107'},{label:'TRRC Tom Redgate Memorial Recovery Center - Long Beach',value:'TRRC',lid:'108'},{label:'TPS Topanga Community Police Station',value:'TPS',lid:'132'},{label:'TOR Torrance Memorial Medical Center',value:'TOR',lid:'109'},{label:'KNCH USC Kenneth Norris, Jr. Cancer Hospital - Los Angeles',value:'KNCH',lid:'110'},{label:'VHH USC Verdugo Hills Hospital - Glendale',value:'VHH',lid:'111'},{label:'VPH Valley Presbyterian Hospital - Van Nuys',value:'VPH',lid:'112'},{label:'VNPS Van Nuys Community Police Station',value:'VNPS',lid:'133'},{label:'WCMC West Covina Medical Center',value:'WCMC',lid:'113'},{label:'HWH West Hills Hospital And Medical Center',value:'HWH',lid:'114'},{label:'WLAPS West Los Angeles Community Police Station',value:'WLAPS',lid:'134'},{label:'WVPS West Valley Community Police Station',value:'WVPS',lid:'135'},{label:'WHH Whittier Hospital Medical Center',value:'WHH',lid:'115'},{label:'WPS Wilshire Community Police Station',value:'WPS',lid:'136'}
);

var loc_med = new Array(
{label:'WMH Adventist Health White Memorial - Los Angeles',value:'WMH',lid:'1'},{label:'ACH Alhambra Hospital Medical Center',value:'ACH',lid:'2'},{label:'AVH Antelope Valley Hospital - Lancaster',value:'AVH',lid:'4'},{label:'BEV Beverly Hospital - Montebello',value:'BEV',lid:'9'},{label:'CAL California Hospital Medical Center - Los Angeles',value:'CAL',lid:'11'},{label:'AHM Catalina Island Medical Center - Avalon',value:'AHM',lid:'14'},{label:'CSM Cedars Sinai Medical Center - Los Angeles',value:'CSM',lid:'15'},{label:'DFM Cedars-Sinai Marina Del Rey Hospital',value:'DFM',lid:'16'},{label:'CNT Centinella Hospital Medical Center - Inglewood',value:'CNT',lid:'17'},{label:'CHH Children\'s Hospital Of Los Angeles',value:'CHH',lid:'18'},{label:'CPM Coast Plaza Hospital - Norwalk',value:'CPM',lid:'20'},{label:'CHP Community Hospital Of Huntington Park',value:'CHP',lid:'23'},{label:'ELA East Los Angeles Doctors Hospital',value:'ELA',lid:'26'},{label:'FPH Emanate Health Foothill Presbyterian Hospital - Glendora',value:'FPH',lid:'27'},{label:'ICH Emanate Health Inter-Community Hospital - Covina',value:'ICH',lid:'28'},{label:'QVH Emanate Health Queen of the Valley Hospital - West Covina',value:'QVH',lid:'29'},{label:'ENH Encino Hospital Medical Center',value:'ENH',lid:'30'},{label:'GAR Garfield Medical Center - Monterey Park',value:'GAR',lid:'33'},{label:'GWT Glendale Adventist Medical Center- Glendale',value:'GWT',lid:'35'},{label:'GMH Glendale Memorial Hospital And Health Center',value:'GMH',lid:'36'},{label:'HEV Glendora Community Hospital',value:'HEV',lid:'37'},{label:'GSH Good Samaritan Hospital - Los Angeles',value:'GSH',lid:'38'},{label:'GEM Greater El Monte Community Hospital',value:'GEM',lid:'39'},{label:'HMN Henry Mayo Newhall Hospital',value:'HMN',lid:'40'},{label:'QOA Hollywood Presbyterian Medical Center',value:'QOA',lid:'41'},{label:'HMH Huntington Memorial Hospital - Pasadena',value:'HMH',lid:'42'},{label:'KFA Kaiser Foundation Hospital - Baldwin Park',value:'KFA',lid:'44'},{label:'KFB Kaiser Foundation Hospital - Downey',value:'KFB',lid:'45'},{label:'KFL Kaiser Foundation Hospital - Los Angeles',value:'KFL',lid:'46'},{label:'KFP Kaiser Foundation Hospital - Panorama City',value:'KFP',lid:'48'},{label:'KFH Kaiser Foundation Hospital - South Bay',value:'KFH',lid:'49'},{label:'KFW Kaiser Foundation Hospital - West LA',value:'KFW',lid:'50'},{label:'KFO Kaiser Foundation Hospital - Woodland Hills',value:'KFO',lid:'51'},{label:'USC LAC+USC Medical Center - Los Angeles',value:'USC',lid:'62'},{label:'HGH LAC/Harbor-UCLA Medical Center - Torrance',value:'HGH',lid:'60'},{label:'DHL Lakewood Regional Medical Center',value:'DHL',lid:'63'},{label:'OVM Los Angeles County Olive View - UCLA Medical Center - Sylmar',value:'OVM',lid:'66'},{label:'MLK Martin Luther King, Jr. Community Hospital - Los Angeles',value:'MLK',lid:'67'},{label:'MHG Memorial Hospital Of Gardena',value:'MHG',lid:'68'},{label:'LBM Memorialcare Long Beach Medical Center',value:'LBM',lid:'69'},{label:'AMH Methodist Hospital Of Southern California - Arcadia',value:'AMH',lid:'71'},{label:'MCP Mission Community Hospital - Panorama Campus',value:'MCP',lid:'73'},{label:'MPH Monterey Park Hospital',value:'MPH',lid:'75'},{label:'NRH Northridge Hospital Medical Center',value:'NRH',lid:'77'},{label:'NOR Norwalk Community Hospital',value:'NOR',lid:'78'},{label:'MID Olympia Medical Center - Los Angeles',value:'MID',lid:'80'},{label:'PLB Pacific Hospital of Long Beach',value:'PLB',lid:'81'},{label:'PAC Pacifica Hospital Of The Valley - Sun Valley',value:'PAC',lid:'82'},{label:'LCH Palmdale Regional Medical Center',value:'LCH',lid:'83'},{label:'DCH PIH Health Hospital - Downey',value:'DCH',lid:'84'},{label:'PIH PIH Health Hospital - Whittier',value:'PIH',lid:'85'},{label:'PVC Pomona Valley Hospital Medical Center',value:'PVC',lid:'86'},{label:'HCH Providence Holy Cross Medical Center - Mission Hills',value:'HCH',lid:'88'},{label:'SPP Providence Little Company Of Mary MC - San Pedro',value:'SPP',lid:'89'},{label:'LCM Providence Little Company Of Mary Medical Center Torrance',value:'LCM',lid:'90'},{label:'SJH Providence Saint John\'s Health Center - Santa Monica',value:'SJH',lid:'91'},{label:'SJS Providence Saint Joseph Medical Center - Burbank',value:'SJS',lid:'92'},{label:'TRM Providence Tarzana Medical Center',value:'TRM',lid:'93'},{label:'UCL Ronald Reagan UCLA Medical Center - Los Angeles',value:'UCL',lid:'95'},{label:'SDC San Dimas Community Hospital',value:'SDC',lid:'96'},{label:'SGC San Gabriel Valley Medical Center',value:'SGC',lid:'97'},{label:'SMH Santa Monica - UCLA Medical Center And Orthopaedic Hospital',value:'SMH',lid:'98'},{label:'SOC Sherman Oaks Hospital',value:'SOC',lid:'99'},{label:'BMC Southern California Hospital at Culver City',value:'BMC',lid:'101'},{label:'SFM St. Francis Medical Center- Lynwood',value:'SFM',lid:'103'},{label:'SMM St. Mary Medical Center - Long Beach',value:'SMM',lid:'104'},{label:'SVH St. Vincent Medical Center - Los Angeles',value:'SVH',lid:'105'},{label:'TOR Torrance Memorial Medical Center',value:'TOR',lid:'109'},{label:'VHH USC Verdugo Hills Hospital - Glendale',value:'VHH',lid:'111'},{label:'VPH Valley Presbyterian Hospital - Van Nuys',value:'VPH',lid:'112'},{label:'HWH West Hills Hospital And Medical Center',value:'HWH',lid:'114'},{label:'WHH Whittier Hospital Medical Center',value:'WHH',lid:'115'}
);

var loc_pd = new Array(
{label:'77PS 77th Community Police Station',value:'77PS',lid:'129'},{label:'CPS Central Community Police Station ',value:'CPS',lid:'116'},{label:'DPS Devonshire Community Police Station',value:'DPS',lid:'117'},{label:'FPS Foothill Community Police Station',value:'FPS',lid:'118'},{label:'HPS Harbor Community Police Station ',value:'HPS',lid:'119'},{label:'HBPS Hollenbeck Community Police Station ',value:'HBPS',lid:'120'},{label:'HWPS Hollywood Community Police Station',value:'HWPS',lid:'121'},{label:'MPS Mission Community Police Station ',value:'MPS',lid:'122'},{label:'NPS Newton Community Police Station ',value:'NPS',lid:'123'},{label:'NHPS North Hollywood Community Police Station',value:'NHPS',lid:'124'},{label:'NEPS Northeast Community Police Station ',value:'NEPS',lid:'125'},{label:'OPS Olympic Community Police Station',value:'OPS',lid:'126'},{label:'PPS Pacific Community Police Station',value:'PPS',lid:'127'},{label:'RPS Rampart Community Police Station ',value:'RPS',lid:'128'},{label:'SEPS Southeast Community Police Station',value:'SEPS',lid:'130'},{label:'SWPS Southwest Community Police Station',value:'SWPS',lid:'131'},{label:'TPS Topanga Community Police Station',value:'TPS',lid:'132'},{label:'VNPS Van Nuys Community Police Station',value:'VNPS',lid:'133'},{label:'WLAPS West Los Angeles Community Police Station',value:'WLAPS',lid:'134'},{label:'WVPS West Valley Community Police Station',value:'WVPS',lid:'135'},{label:'WPS Wilshire Community Police Station',value:'WPS',lid:'136'}
);

var loc_fd = new Array(
{label:'LAFD 1 LAFD Station 1',value:'LAFD 1',lid:'193'},{label:'LAFD 10 LAFD Station 10',value:'LAFD 10',lid:'160'},{label:'LAFD 100 LAFD Station 100',value:'LAFD 100',lid:'229'},{label:'LAFD 101 LAFD Station 101',value:'LAFD 101',lid:'162'},{label:'LAFD 102 LAFD Station 102',value:'LAFD 102',lid:'157'},{label:'LAFD 103 LAFD Station 103',value:'LAFD 103',lid:'180'},{label:'LAFD 104 LAFD Station 104',value:'LAFD 104',lid:'238'},{label:'LAFD 105 LAFD Station 105',value:'LAFD 105',lid:'227'},{label:'LAFD 106 LAFD Station 106',value:'LAFD 106',lid:'194'},{label:'LAFD 107 LAFD Station 107',value:'LAFD 107',lid:'189'},{label:'LAFD 108 LAFD Station 108',value:'LAFD 108',lid:'155'},{label:'LAFD 109 LAFD Station 109',value:'LAFD 109',lid:'175'},{label:'LAFD 11 LAFD Station 11',value:'LAFD 11',lid:'181'},{label:'LAFD 110 LAFD Station 110',value:'LAFD 110',lid:'198'},{label:'LAFD 111 LAFD Station 111',value:'LAFD 111',lid:'242'},{label:'LAFD 112 LAFD Station 112',value:'LAFD 112',lid:'213'},{label:'LAFD 114 LAFD Station 114',value:'LAFD 114',lid:'176'},{label:'LAFD 12 LAFD Station 12',value:'LAFD 12',lid:'226'},{label:'LAFD 13 LAFD Station 13',value:'LAFD 13',lid:'195'},{label:'LAFD 14 LAFD Station 14',value:'LAFD 14',lid:'204'},{label:'LAFD 15 LAFD Station 15',value:'LAFD 15',lid:'199'},{label:'LAFD 16 LAFD Station 16',value:'LAFD 16',lid:'187'},{label:'LAFD 17 LAFD Station 17',value:'LAFD 17',lid:'174'},{label:'LAFD 18 LAFD Station 18',value:'LAFD 18',lid:'152'},{label:'LAFD 19 LAFD Station 19',value:'LAFD 19',lid:'153'},{label:'LAFD 2 LAFD Station 2',value:'LAFD 2',lid:'185'},{label:'LAFD 20 LAFD Station 20',value:'LAFD 20',lid:'191'},{label:'LAFD 21 LAFD Station 21',value:'LAFD 21',lid:'150'},{label:'LAFD 23 LAFD Station 23',value:'LAFD 23',lid:'177'},{label:'LAFD 24 LAFD Station 24',value:'LAFD 24',lid:'241'},{label:'LAFD 25 LAFD Station 25',value:'LAFD 25',lid:'197'},{label:'LAFD 26 LAFD Station 26',value:'LAFD 26',lid:'186'},{label:'LAFD 27 LAFD Station 27',value:'LAFD 27',lid:'158'},{label:'LAFD 28 LAFD Station 28',value:'LAFD 28',lid:'149'},{label:'LAFD 29 LAFD Station 29',value:'LAFD 29',lid:'208'},{label:'LAFD 3 LAFD Station 3',value:'LAFD 3',lid:'144'},{label:'LAFD 33 LAFD Station 33',value:'LAFD 33',lid:'228'},{label:'LAFD 34 LAFD Station 34',value:'LAFD 34',lid:'205'},{label:'LAFD 35 LAFD Station 35',value:'LAFD 35',lid:'172'},{label:'LAFD 36 LAFD Station 36',value:'LAFD 36',lid:'139'},{label:'LAFD 37 LAFD Station 37',value:'LAFD 37',lid:'146'},{label:'LAFD 38 LAFD Station 38',value:'LAFD 38',lid:'154'},{label:'LAFD 39 LAFD Station 39',value:'LAFD 39',lid:'166'},{label:'LAFD 4 LAFD Station 4',value:'LAFD 4',lid:'216'},{label:'LAFD 40 LAFD Station 40',value:'LAFD 40',lid:'203'},{label:'LAFD 41 LAFD Station 41',value:'LAFD 41',lid:'165'},{label:'LAFD 42 LAFD Station 42',value:'LAFD 42',lid:'188'},{label:'LAFD 43 LAFD Station 43',value:'LAFD 43',lid:'206'},{label:'LAFD 44 LAFD Station 44',value:'LAFD 44',lid:'161'},{label:'LAFD 46 LAFD Station 46',value:'LAFD 46',lid:'212'},{label:'LAFD 47 LAFD Station 47',value:'LAFD 47',lid:'217'},{label:'LAFD 48 LAFD Station 48',value:'LAFD 48',lid:'173'},{label:'LAFD 49 LAFD Station 49',value:'LAFD 49',lid:'207'},{label:'LAFD 5 LAFD Station 5',value:'LAFD 5',lid:'239'},{label:'LAFD 50 LAFD Station 50',value:'LAFD 50',lid:'200'},{label:'LAFD 51 LAFD Station 51',value:'LAFD 51',lid:'141'},{label:'LAFD 52 LAFD Station 52',value:'LAFD 52',lid:'218'},{label:'LAFD 55 LAFD Station 55',value:'LAFD 55',lid:'214'},{label:'LAFD 56 LAFD Station 56',value:'LAFD 56',lid:'196'},{label:'LAFD 57 LAFD Station 57',value:'LAFD 57',lid:'235'},{label:'LAFD 58 LAFD Station 58',value:'LAFD 58',lid:'171'},{label:'LAFD 59 LAFD Station 59',value:'LAFD 59',lid:'148'},{label:'LAFD 6 LAFD Station 6',value:'LAFD 6',lid:'202'},{label:'LAFD 60 LAFD Station 60',value:'LAFD 60',lid:'222'},{label:'LAFD 61 LAFD Station 61',value:'LAFD 61',lid:'225'},{label:'LAFD 62 LAFD Station 62',value:'LAFD 62',lid:'151'},{label:'LAFD 63 LAFD Station 63',value:'LAFD 63',lid:'184'},{label:'LAFD 64 LAFD Station 64',value:'LAFD 64',lid:'145'},{label:'LAFD 65 LAFD Station 65',value:'LAFD 65',lid:'178'},{label:'LAFD 66 LAFD Station 66',value:'LAFD 66',lid:'183'},{label:'LAFD 67 LAFD Station 67',value:'LAFD 67',lid:'223'},{label:'LAFD 68 LAFD Station 68',value:'LAFD 68',lid:'220'},{label:'LAFD 69 LAFD Station 69',value:'LAFD 69',lid:'169'},{label:'LAFD 7 LAFD Station 7',value:'LAFD 7',lid:'168'},{label:'LAFD 70 LAFD Station 70',value:'LAFD 70',lid:'243'},{label:'LAFD 71 LAFD Station 71',value:'LAFD 71',lid:'143'},{label:'LAFD 72 LAFD Station 72',value:'LAFD 72',lid:'230'},{label:'LAFD 73 LAFD Station 73',value:'LAFD 73',lid:'233'},{label:'LAFD 74 LAFD Station 74',value:'LAFD 74',lid:'234'},{label:'LAFD 75 LAFD Station 75',value:'LAFD 75',lid:'170'},{label:'LAFD 76 LAFD Station 76',value:'LAFD 76',lid:'201'},{label:'LAFD 77 LAFD Station 77',value:'LAFD 77',lid:'240'},{label:'LAFD 78 LAFD Station 78',value:'LAFD 78',lid:'209'},{label:'LAFD 79 LAFD Station 79',value:'LAFD 79',lid:'179'},{label:'LAFD 8 LAFD Station 8',value:'LAFD 8',lid:'147'},{label:'LAFD 80 LAFD Station 80',value:'LAFD 80',lid:'232'},{label:'LAFD 81 LAFD Station 81',value:'LAFD 81',lid:'164'},{label:'LAFD 82 LAFD Station 82',value:'LAFD 82',lid:'224'},{label:'LAFD 83 LAFD Station 83',value:'LAFD 83',lid:'219'},{label:'LAFD 84 LAFD Station 84',value:'LAFD 84',lid:'190'},{label:'LAFD 85 LAFD Station 85',value:'LAFD 85',lid:'159'},{label:'LAFD 86 LAFD Station 86',value:'LAFD 86',lid:'211'},{label:'LAFD 87 LAFD Station 87',value:'LAFD 87',lid:'140'},{label:'LAFD 88 LAFD Station 88',value:'LAFD 88',lid:'221'},{label:'LAFD 89 LAFD Station 89',value:'LAFD 89',lid:'231'},{label:'LAFD 9 LAFD Station 9',value:'LAFD 9',lid:'210'},{label:'LAFD 90 LAFD Station 90',value:'LAFD 90',lid:'236'},{label:'LAFD 91 LAFD Station 91',value:'LAFD 91',lid:'167'},{label:'LAFD 92 LAFD Station 92',value:'LAFD 92',lid:'142'},{label:'LAFD 93 LAFD Station 93',value:'LAFD 93',lid:'182'},{label:'LAFD 94 LAFD Station 94',value:'LAFD 94',lid:'215'},{label:'LAFD 95 LAFD Station 95',value:'LAFD 95',lid:'138'},{label:'LAFD 96 LAFD Station 96',value:'LAFD 96',lid:'192'},{label:'LAFD 97 LAFD Station 97',value:'LAFD 97',lid:'237'},{label:'LAFD 98 LAFD Station 98',value:'LAFD 98',lid:'156'},{label:'LAFD 99 LAFD Station 99',value:'LAFD 99',lid:'163'}
);

var loc_psy = new Array(
{label:'ACOC Aurora Charter Oak - Covina',value:'ACOC',lid:'6'},{label:'ALEH Aurora Las Encinas Hospital - Pasadena',value:'ALEH',lid:'7'},{label:'BHCA BHC Alhambra Hospital',value:'BHCA',lid:'10'},{label:'COLH College Hospital - Cerritos',value:'COLH',lid:'21'},{label:'DAHT Del Amo Hospital - Torrance',value:'DAHT',lid:'24'},{label:'DOSH Department Of State Hospital - Metropolitan - Norwalk',value:'DOSH',lid:'25'},{label:'EXRP Exodus Recovery PHF - Culver City',value:'EXRP',lid:'31'},{label:'GHMH Gateways Hospital And Mental Health Center - Los Angeles',value:'GHMH',lid:'34'},{label:'JEKM Joyce Eisenberg Keefer Medical Center - Reseda',value:'JEKM',lid:'43'},{label:'KCMH Kedren Community Mental Health Center - Los Angeles',value:'KCMH',lid:'53'},{label:'LCPH La Casa Psychiatric Health Facility - Long Beach',value:'LCPH',lid:'59'},{label:'MPTH Motion Picture And Television Hospital - Woodland Hills',value:'MPTH',lid:'76'},{label:'OVPH Ocean View Psychiatric Health Facility - Long Beach',value:'OVPH',lid:'79'},{label:'RNLA Resnick Neuropsychiatric Hospital At UCLA - Los Angeles',value:'RNLA',lid:'94'},{label:'SVAP Star View Adolescent - PHF - Torrance',value:'SVAP',lid:'106'},{label:'TZTC Tarzana Treatment Center',value:'TZTC',lid:'107'}
);

var loc_reh = new Array(
{label:'ARCP American Recovery Center - Pomona',value:'ARCP',lid:'3'},{label:'TRRC Tom Redgate Memorial Recovery Center - Long Beach',value:'TRRC',lid:'108'}
);

var loc_sta = new Array(

);

var people = new Array(
{label:'ACS CMD Michael Schlenker',value:'ACS CMD Michael Schlenker',mid:'40'},{label:'AJ6DL Darren La Groe',value:'AJ6DL Darren La Groe',mid:'9'},{label:'ARES CMD David Ahrendts',value:'ARES CMD David Ahrendts',mid:'31'},{label:'DEC Roozy Moabery',value:'DEC Roozy Moabery',mid:'30'},{label:'IC 1 Bill Coss',value:'IC 1 Bill Coss',mid:'41'},{label:'IC 2 Brian Linse',value:'IC 2 Brian Linse',mid:'42'},{label:'K6AUR Alexander Auerbach',value:'K6AUR Alexander Auerbach',mid:'26'},{label:'K6OAT Dan Ruderman',value:'K6OAT Dan Ruderman',mid:'10'},{label:'K9SDS Stephen Smedberg',value:'K9SDS Stephen Smedberg',mid:'19'},{label:'KJ6UVQ Dennis Payne',value:'KJ6UVQ Dennis Payne',mid:'7'},{label:'KK6DA David Ahrendts',value:'KK6DA David Ahrendts',mid:'5'},{label:'KM6JWV Martin Rumpf',value:'KM6JWV Martin Rumpf',mid:'27'},{label:'KM6LFE Jessica Wood',value:'KM6LFE Jessica Wood',mid:'28'},{label:'KM6RWG Scott Spooner',value:'KM6RWG Scott Spooner',mid:'18'},{label:'KM6WKA James Butler',value:'KM6WKA James Butler',mid:'1'},{label:'LOS FLZ 2 Mike Hain',value:'LOS FLZ 2 Mike Hain',mid:'34'},{label:'LOS FLZ 2 Chris Seitz',value:'LOS FLZ 2 Chris Seitz',mid:'33'},{label:'N4XRO David Poole',value:'N4XRO David Poole',mid:'17'},{label:'NR6V Dan Tomlinson',value:'NR6V Dan Tomlinson',mid:'11'},{label:'SCRIBE James Butler',value:'SCRIBE James Butler',mid:'29'},{label:'SUPPLY Nathan Wolfstein',value:'SUPPLY Nathan Wolfstein',mid:'32'},{label:'UNITED 1 Mercedes Prado',value:'UNITED 1 Mercedes Prado',mid:'35'},{label:'UNITED 2 Jessica Wood',value:'UNITED 2 Jessica Wood',mid:'36'},{label:'UNITED 3 Kelly Anderson',value:'UNITED 3 Kelly Anderson',mid:'37'},{label:'W0DHG David Goldenberg',value:'W0DHG David Goldenberg',mid:'8'},{label:'W1EH Roozy Moabery',value:'W1EH Roozy Moabery',mid:'4'},{label:'W6AH Chris Mattia',value:'W6AH Chris Mattia',mid:'12'},{label:'WA6P Dean Cuadra',value:'WA6P Dean Cuadra',mid:'6'},{label:'WEST 1 Dennis Smith',value:'WEST 1 Dennis Smith',mid:'38'},{label:'WEST 2 Paul Jenkins',value:'WEST 2 Paul Jenkins',mid:'39'}
);

var people_ops = new Array(
{label:'ACS CMD Michael Schlenker',value:'ACS CMD Michael Schlenker',mid:'40'},{label:'AJ6DL Darren La Groe',value:'AJ6DL Darren La Groe',mid:'9'},{label:'ARES CMD David Ahrendts',value:'ARES CMD David Ahrendts',mid:'31'},{label:'DEC Roozy Moabery',value:'DEC Roozy Moabery',mid:'30'},{label:'IC 1 Bill Coss',value:'IC 1 Bill Coss',mid:'41'},{label:'IC 2 Brian Linse',value:'IC 2 Brian Linse',mid:'42'},{label:'K6AUR Alexander Auerbach',value:'K6AUR Alexander Auerbach',mid:'26'},{label:'K6OAT Dan Ruderman',value:'K6OAT Dan Ruderman',mid:'10'},{label:'K9SDS Stephen Smedberg',value:'K9SDS Stephen Smedberg',mid:'19'},{label:'KJ6UVQ Dennis Payne',value:'KJ6UVQ Dennis Payne',mid:'7'},{label:'KK6DA David Ahrendts',value:'KK6DA David Ahrendts',mid:'5'},{label:'KM6JWV Martin Rumpf',value:'KM6JWV Martin Rumpf',mid:'27'},{label:'KM6LFE Jessica Wood',value:'KM6LFE Jessica Wood',mid:'28'},{label:'KM6RWG Scott Spooner',value:'KM6RWG Scott Spooner',mid:'18'},{label:'KM6WKA James Butler',value:'KM6WKA James Butler',mid:'1'},{label:'LOS FLZ 2 Mike Hain',value:'LOS FLZ 2 Mike Hain',mid:'34'},{label:'LOS FLZ 2 Chris Seitz',value:'LOS FLZ 2 Chris Seitz',mid:'33'},{label:'N4XRO David Poole',value:'N4XRO David Poole',mid:'17'},{label:'NR6V Dan Tomlinson',value:'NR6V Dan Tomlinson',mid:'11'},{label:'SCRIBE James Butler',value:'SCRIBE James Butler',mid:'29'},{label:'SUPPLY Nathan Wolfstein',value:'SUPPLY Nathan Wolfstein',mid:'32'},{label:'UNITED 1 Mercedes Prado',value:'UNITED 1 Mercedes Prado',mid:'35'},{label:'UNITED 2 Jessica Wood',value:'UNITED 2 Jessica Wood',mid:'36'},{label:'UNITED 3 Kelly Anderson',value:'UNITED 3 Kelly Anderson',mid:'37'},{label:'W0DHG David Goldenberg',value:'W0DHG David Goldenberg',mid:'8'},{label:'W1EH Roozy Moabery',value:'W1EH Roozy Moabery',mid:'4'},{label:'W6AH Chris Mattia',value:'W6AH Chris Mattia',mid:'12'},{label:'WA6P Dean Cuadra',value:'WA6P Dean Cuadra',mid:'6'},{label:'WEST 1 Dennis Smith',value:'WEST 1 Dennis Smith',mid:'38'},{label:'WEST 2 Paul Jenkins',value:'WEST 2 Paul Jenkins',mid:'39'}
);

var people_net = new Array(

);

var people_adm = new Array(

);

var incidents = new Array(
{label:'',value:'New Incident',iid:0},{label:'FIRE: HH EVAC DRILL: Hollywood Hills Evac Drill',value:'Hollywood Hills Evac Drill',iid:'2'},{label:'FIRE: OAKFIRE: Oak Fire',value:'Oak Fire',iid:'1'}
);

var inc_fire = new Array(
{label:'FIRE: HH EVAC DRILL: Hollywood Hills Evac Drill',value:'Hollywood Hills Evac Drill',iid:'2'},{label:'FIRE: OAKFIRE: Oak Fire',value:'Oak Fire',iid:'1'}
);

var inc_quak = new Array(

);

var inc_terr = new Array(

);

var inc_othr = new Array(

);

var inc_flod = new Array(

);

var inc_evnt = new Array(
);

var netcontrols = new Array(
{label:'n1hen: Oak Fire: Calabasas',value:'n1hen: Oak Fire: Calabasas',ncid:'1'},{label:'N1HEN: Oak Fire',value:'N1HEN: Oak Fire',ncid:'2'}
);

var typs = new Array(
'','A Action','E Event','H HSA Poll','M MCI Poll','R Resource Request','L Relay Request'
);


