
<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<script>

  function setVisibility(){

      var option = document.getElementById("form").elements[0].value;
      var keyword_box = document.getElementById("keyword_text");
      if(option == "Verilen keyword ile arama sonucu"){
          keyword_box.style.visibility = "visible";
      }

      else{
          keyword_box.style.visibility = "hidden";
      }
  }

</script>



<h2>Report & Analyze</h2>

<form method="post" id="form" action="">

  <select name="taskOption" onchange="setVisibility()">
    <option value="En çok entry giren yazar">En çok entry giren yazar</option>
    <option value="En çok upvote alan entryler ve yazarları">En çok upvote alan entryler ve yazarları</option>
    <option value="En çok downvote alan entryler ve yazarları">En çok downvote alan entryler ve yazarları</option>
    <option value="En çok entry girilen titlelar">En çok entry girilen titlelar</option>
    <option value="Verilen keyword ile arama sonucu">Keyword ile arama yap</option>
  </select>

  <input type="text" id="keyword_text" name="keyword" style="visibility:hidden;"><br>
  
   <select name="siralama">
    <option value="Top 10">Top 10</option>
    <option value="Top 20">Top 20</option>
    <option value="Top 50">Top 50</option>
    <option value="Top 100">Top 100</option>
    <option value="Show Everything">Show Everything</option>
  </select>

  <input type="submit" name="submit" value="Search">

</form>



<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3000);
$selectOption = $keyword="";

$selectOption = $_POST["taskOption"];
$secilenKeyword = $_POST["keyword"];
$limiter = $_POST["siralama"];
$counterLimit = 0;
if ($limiter =="Top 10"){
	$counterLimit = 10;
}
else if ($limiter =="Top 20"){
	$counterLimit = 20;
}
else if ($limiter =="Top 50"){
	$counterLimit = 50;
}
else if ($limiter =="Top 100"){
	$counterLimit = 100;
}
else {
	$counterLimit = 0;
}

if ($selectOption == "Verilen keyword ile arama sonucu") {
    echo "<br>";
	echo $selectOption . ": " . $keyword;
	echo "<br>";
}
else{
	echo "<br>";
    echo $selectOption . ":";
    echo "<br>";	
}

$file1 = 'C:\xampp\htdocs\data38500000-38750000.json';
$file2 = 'C:\xampp\htdocs\data38750000-38900000.json';
$file3 = 'C:\xampp\htdocs\data38900000-39000000.json';
$file4 = 'C:\xampp\htdocs\data39000000-39250000.json';
$file5 = 'C:\xampp\htdocs\data39250000-39500000.json';
$file6 = 'C:\xampp\htdocs\data39500000-39600000.json';
$file7 = 'C:\xampp\htdocs\data39600000-39750000.json';
$file8 = 'C:\xampp\htdocs\data39750000-40000000.json';

$category_dict1 = json_decode(file_get_contents($file1),true);
$category_dict2 = json_decode(file_get_contents($file2),true);
$category_dict3 = json_decode(file_get_contents($file3),true);
$category_dict4 = json_decode(file_get_contents($file4),true);
$category_dict5 = json_decode(file_get_contents($file5),true);
$category_dict6 = json_decode(file_get_contents($file6),true);
$category_dict7 = json_decode(file_get_contents($file7),true);
$category_dict8 = json_decode(file_get_contents($file8),true);

$yazarlar = array();
$upvote = array();
$downvote = array();
$enUzunTitle = array();
$keywordSayilari = array();
$stopwords = array(
	"ama",
	"amma",
	"anca",
	"ancak",
	"belki",
	"çünkü",
	"dahi",
	"eğer",
	"emme",
	"fakat",
	"gah",
	"gerek",
	"hakeza",
	"halbuki",
	"hatta",
	"hele",
	"hem",
	"hoş",
	"ile",
	"ile",
	"imdi",
	"ister",
	"kah",
	"keşke",
	"keza",
	"kezalik",
	"kim",
	"lakin",
	"madem",
	"mademki",
	"mamafih",
	"meğer",
	"meğerki",
	"meğerse",
	"netekim",
	"neyse",
	"nitekim",
	"oysa",
	"oysaki",
	"şayet",
	"velev",
	"velhasıl",
	"velhasılıkelam",
	"veya",
	"veyahut",
	"yahut",
	"yalnız",
	"yani",
	"yok",
	"yoksa",
	"zira",
	"acaba",
	"acep",
	"açıkça",
	"açıkçası",
	"adamakıllı",
	"adeta",
	"bazen",
	"bazı",
	"bilcümle",
	"binaen",
	"binaenaleyh",
	"bir",
	"biraz",
	"birazdan",
	"birden",
	"birden",
	"birdenbire",
	"birice",
	"birlikte",
	"bitevi",
	"biteviye",
	"bittabi",
	"bizatihi",
	"bizce",
	"bizcileyin",
	"bizden",
	"bizzat",
	"boşuna",
	"böyle",
	"böylece",
	"böylecene",
	"böylelikle",
	"böylemesine",
	"böylesine",
	"buracıkta",
	"burada",
	"buradan",
	"büsbütün",
	"çabuk",
	"çabukça",
	"çeşitli",
	"çoğu",
	"çoğun",
	"çoğunca",
	"çoğunlukla",
	"çok",
	"çokça",
	"çokluk",
	"çoklukla",
	"cuk",
	"daha",
	"dahil",
	"dahilen",
	"daima",
	"demin",
	"demincek",
	"deminden",
	"derakap",
	"derhal",
	"derken",
	"diye",
	"elbet",
	"elbette",
	"enikonu",
	"epey",
	"epeyce",
	"epeyi",
	"esasen",
	"esnasında",
	"etraflı",
	"etraflıca",
	"evleviyetle",
	"evvel",
	"evvela",
	"evvelce",
	"evvelden",
	"evvelemirde",
	"evveli",
	"gayet",
	"gayetle",
	"gayri",
	"gayrı",
	"geçende",
	"geçenlerde",
	"gene",
	"gerçi",
	"gibi",
	"gibilerden",
	"gibisinden",
	"gine",
	"halen",
	"halihazırda",
	"haliyle",
	"handiyse",
	"hani",
	"hasılı",
	"hulasaten",
	"iken",
	"illa",
	"illaki",
	"itibarıyla",
	"iyice",
	"iyicene",
	"kala",
	"kez",
	"kısaca",
	"külliyen",
	"lütfen",
	"nasıl",
	"nasılsa",
	"nazaran",
	"neden",
	"nedeniyle",
	"nedense",
	"nerde",
	"nerden",
	"nerdeyse",
	"nerede",
	"nereden",
	"neredeyse",
	"nereye",
	"neye",
	"neyi",
	"nice",
	"niçin",
	"nihayet",
	"nihayetinde",
	"niye",
	"oldu",
	"oldukça",
	"olur",
	"onca",
	"önce",
	"önceden",
	"önceleri",
	"öncelikle",
	"onculayın",
	"ondan",
	"oracık",
	"oracıkta",
	"orada",
	"oradan",
	"oranca",
	"oranla",
	"oraya",
	"öyle",
	"öylece",
	"öylelikle",
	"öylemesine",
	"pek",
	"pekala",
	"pekçe",
	"peki",
	"peyderpey",
	"sadece",
	"sahi",
	"sahiden",
	"sanki",
	"sonra",
	"sonradan",
	"sonraları",
	"sonunda",
	"şöyle",
	"şuncacık",
	"şuracıkta",
	"tabii",
	"tam",
	"tamam",
	"tamamen",
	"tamamıyla",
	"tek",
	"vasıtasıyla",
	"yakinen",
	"yakında",
	"yakından",
	"yakınlarda",
	"yalnız",
	"yalnızca",
	"yeniden",
	"yenilerde",
	"yine",
	"yok",
	"yoluyla",
	"yüzünden",
	"zaten",
	"zati",
	"ait",
	"bari",
	"beri",
	"bile",
	"değin",
	"dek",
	"denli",
	"doğru",
	"dolayı",
	"dolayısıyla",
	"gelgelelim",
	"gibi",
	"gırla",
	"göre",
	"hasebiyle",
	"için",
	"ila",
	"ile",
	"ilen",
	"indinde",
	"inen",
	"kadar",
	"kaffesi",
	"karşın",
	"kelli",
	"Leh",
	"maada",
	"mebni",
	"naşi",
	"rağmen",
	"üzere",
	"zarfında",
	"öbür",
	"bana",
	"başkası",
	"ben",
	"beriki",
	"birbiri",
	"birçoğu",
	"biri",
	"birileri",
	"birisi",
	"birkaçı",
	"biz",
	"bizimki",
	"buna",
	"bunda",
	"bundan",
	"bunlar",
	"bunu",
	"bunun",
	"burası",
	"çoğu",
	"çoğu",
	"çokları",
	"çoklarınca",
	"cümlesi",
	"değil",
	"diğeri",
	"filanca",
	"hangisi",
	"hepsi",
	"hiçbiri",
	"iş",
	"kaçı",
	"kaynak",
	"kendi",
	"kim",
	"kimi",
	"kimisi",
	"kimse",
	"kimse",
	"kimsecik",
	"kimsecikler",
	"nere",
	"neresi",
	"öbürkü",
	"öbürü",
	"ona",
	"onda",
	"ondan",
	"onlar",
	"onu",
	"onun",
	"öteki",
	"ötekisi",
	"öz",
	"sana",
	"sen",
	"siz",
	"şuna",
	"şunda",
	"şundan",
	"şunlar",
	"şunu",
	"şunun",
	"şura",
	"şuracık",
	"şurası"
	);


if ($selectOption == "Verilen keyword ile arama sonucu") {
	#ilk dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ilk dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ilk dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ilk dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ilk dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ilk dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ilk dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ikinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ikinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ikinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ikinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ikinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ikinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ucuncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ucuncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ucuncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ucuncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#ucuncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#ucuncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#dorduncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#dorduncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#dorduncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#dorduncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#dorduncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#dorduncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#besinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#besinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#besinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#besinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#besinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#besinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#altinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#altinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#altinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#altinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#altinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#altinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}

	#yedinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#yedinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#yedinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#yedinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#yedinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#yedinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#yedinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#sekizinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#sekizinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#sekizinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#sekizinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}	
	#sekizinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	#sekizinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$keywords = array();
			$keywords = explode(" ",$category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]);
			for($keys = 0; $keys<sizeOf($keywords); $keys++){
				# to keep letters, numbers & underscore
				$keywords[$keys] = strtolower($keywords[$keys]);
				$keywords[$keys] = preg_replace('/[^a-z0-9üğöçşı]+/i', '', $keywords[$keys]);
				$keywords[$keys] = preg_replace("/(?![.,=$'€%-])\p{P}/u", "", $keywords[$keys]);
				
				if($keywords[$keys] == "" || in_array($keywords[$keys],$stopwords)){
					continue;
				}
				
				if(array_key_exists($keywords[$keys],$keywordSayilari)){
					$a = $keywordSayilari[$keywords[$keys]];
					$keywordSayilari[$keywords[$keys]] = ++$a;
				}		
				else{
					$keywordSayilari[$keywords[$keys]] = 1 ;
				}
			}
		}
	}
	
	$secilenKeyword = strtolower($secilenKeyword);
	echo "Key to Find: $secilenKeyword  Frequence: $keywordSayilari[$secilenKeyword]";

	
}
if ($selectOption == "En çok entry girilen titlelar") {
	#ilk dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/anket/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/anket/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/anket/"][$kategoriLen])-1;
	}
	#ilk dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/bilim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/bilim/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/bilim/"][$kategoriLen])-1;
	}
	#ilk dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cinsellik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/cinsellik/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/cinsellik/"][$kategoriLen])-1;
	}	
	#ilk dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cografya-gezi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/cografya-gezi/"][$kategoriLen])-1;
	}
	#ilk dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/dini/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/dini/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/dini/"][$kategoriLen])-1;
	}
	#ilk dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/diziler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/diziler/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/diziler/"][$kategoriLen])-1;
	}
	#ilk dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/edebiyat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/edebiyat/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/edebiyat/"][$kategoriLen])-1;
	}
	#ilk dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/egitim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/egitim/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/egitim/"][$kategoriLen])-1;
	}
	#ilk dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/ekonomi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/ekonomi/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/ekonomi/"][$kategoriLen])-1;
	}
	#ilk dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/haber/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/haber/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/haber/"][$kategoriLen])-1;
	}
	#ilk dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/igrenc/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/igrenc/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/igrenc/"][$kategoriLen])-1;
	}
	#ilk dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/iliskiler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/iliskiler/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/iliskiler/"][$kategoriLen])-1;
	}
	#ilk dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/internet/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/internet/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/internet/"][$kategoriLen])-1;
	}
	#ilk dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/irkci/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/irkci/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/irkci/"][$kategoriLen])-1;
	}
	#ilk dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/moda/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/moda/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/moda/"][$kategoriLen])-1;
	}
	#ilk dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/muzik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/muzik/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/muzik/"][$kategoriLen])-1;
	}
	#ilk dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/nickalti/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/nickalti/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/nickalti/"][$kategoriLen])-1;
	}
	#ilk dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/oyunlar/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/oyunlar/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/oyunlar/"][$kategoriLen])-1;
	}
	#ilk dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/saglik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/saglik/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/saglik/"][$kategoriLen])-1;
	}
	#ilk dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sanat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/sanat/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/sanat/"][$kategoriLen])-1;
	}
	#ilk dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sinema/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/sinema/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/sinema/"][$kategoriLen])-1;
	}
	#ilk dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/siyaset/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/siyaset/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/siyaset/"][$kategoriLen])-1;
	}
	#ilk dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sozlukici/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/sozlukici/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/sozlukici/"][$kategoriLen])-1;
	}
	#ilk dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/spor/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/spor/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/spor/"][$kategoriLen])-1;
	}
	#ilk dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/tarih/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/tarih/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/tarih/"][$kategoriLen])-1;
	}
	#ilk dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/teknoloji/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/teknoloji/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/teknoloji/"][$kategoriLen])-1;
	}	
	#ilk dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/televizyon/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/televizyon/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/televizyon/"][$kategoriLen])-1;
	}
	#ilk dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yasam/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/yasam/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/yasam/"][$kategoriLen])-1;
	}
	#ilk dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yeme-icme/"]); $kategoriLen++){
		$enUzunTitle[$category_dict1["/kategori/yeme-icme/"][$kategoriLen][0]] = sizeOf($category_dict1["/kategori/yeme-icme/"][$kategoriLen])-1;
	}
	#ikinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/anket/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/anket/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/anket/"][$kategoriLen])-1;
	}
	#ikinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/bilim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/bilim/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/bilim/"][$kategoriLen])-1;
	}
	#ikinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cinsellik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/cinsellik/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/cinsellik/"][$kategoriLen])-1;
	}	
	#ikinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cografya-gezi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/cografya-gezi/"][$kategoriLen])-1;
	}
	#ikinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/dini/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/dini/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/dini/"][$kategoriLen])-1;
	}
	#ikinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/diziler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/diziler/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/diziler/"][$kategoriLen])-1;
	}
	#ikinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/edebiyat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/edebiyat/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/edebiyat/"][$kategoriLen])-1;
	}
	#ikinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/egitim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/egitim/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/egitim/"][$kategoriLen])-1;
	}
	#ikinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/ekonomi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/ekonomi/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/ekonomi/"][$kategoriLen])-1;
	}
	#ikinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/haber/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/haber/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/haber/"][$kategoriLen])-1;
	}
	#ikinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/igrenc/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/igrenc/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/igrenc/"][$kategoriLen])-1;
	}
	#ikinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/iliskiler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/iliskiler/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/iliskiler/"][$kategoriLen])-1;
	}
	#ikinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/internet/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/internet/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/internet/"][$kategoriLen])-1;
	}
	#ikinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/irkci/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/irkci/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/irkci/"][$kategoriLen])-1;
	}
	#ikinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/moda/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/moda/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/moda/"][$kategoriLen])-1;
	}
	#ikinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/muzik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/muzik/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/muzik/"][$kategoriLen])-1;
	}
	#ikinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/nickalti/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/nickalti/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/nickalti/"][$kategoriLen])-1;
	}
	#ikinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/oyunlar/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/oyunlar/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/oyunlar/"][$kategoriLen])-1;
	}
	#ikinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/saglik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/saglik/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/saglik/"][$kategoriLen])-1;
	}
	#ikinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sanat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/sanat/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/sanat/"][$kategoriLen])-1;
	}
	#ikinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sinema/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/sinema/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/sinema/"][$kategoriLen])-1;
	}
	#ikinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/siyaset/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/siyaset/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/siyaset/"][$kategoriLen])-1;
	}
	#ikinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sozlukici/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/sozlukici/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/sozlukici/"][$kategoriLen])-1;
	}
	#ikinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/spor/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/spor/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/spor/"][$kategoriLen])-1;
	}
	#ikinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/tarih/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/tarih/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/tarih/"][$kategoriLen])-1;
	}
	#ikinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/teknoloji/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/teknoloji/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/teknoloji/"][$kategoriLen])-1;
	}	
	#ikinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/televizyon/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/televizyon/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/televizyon/"][$kategoriLen])-1;
	}
	#ikinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yasam/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/yasam/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/yasam/"][$kategoriLen])-1;
	}
	#ikinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yeme-icme/"]); $kategoriLen++){
		$enUzunTitle[$category_dict2["/kategori/yeme-icme/"][$kategoriLen][0]] = sizeOf($category_dict2["/kategori/yeme-icme/"][$kategoriLen])-1;
	}
	#ucuncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/anket/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/anket/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/anket/"][$kategoriLen])-1;
	}
	#ucuncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/bilim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/bilim/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/bilim/"][$kategoriLen])-1;
	}
	#ucuncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cinsellik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/cinsellik/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/cinsellik/"][$kategoriLen])-1;
	}	
	#ucuncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cografya-gezi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/cografya-gezi/"][$kategoriLen])-1;
	}
	#ucuncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/dini/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/dini/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/dini/"][$kategoriLen])-1;
	}
	#ucuncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/diziler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/diziler/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/diziler/"][$kategoriLen])-1;
	}
	#ucuncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/edebiyat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/edebiyat/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/edebiyat/"][$kategoriLen])-1;
	}
	#ucuncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/egitim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/egitim/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/egitim/"][$kategoriLen])-1;
	}
	#ucuncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/ekonomi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/ekonomi/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/ekonomi/"][$kategoriLen])-1;
	}
	#ucuncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/haber/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/haber/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/haber/"][$kategoriLen])-1;
	}
	#ucuncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/igrenc/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/igrenc/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/igrenc/"][$kategoriLen])-1;
	}
	#ucuncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/iliskiler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/iliskiler/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/iliskiler/"][$kategoriLen])-1;
	}
	#ucuncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/internet/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/internet/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/internet/"][$kategoriLen])-1;
	}
	#ucuncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/irkci/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/irkci/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/irkci/"][$kategoriLen])-1;
	}
	#ucuncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/moda/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/moda/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/moda/"][$kategoriLen])-1;
	}
	#ucuncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/muzik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/muzik/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/muzik/"][$kategoriLen])-1;
	}
	#ucuncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/nickalti/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/nickalti/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/nickalti/"][$kategoriLen])-1;
	}
	#ucuncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/oyunlar/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/oyunlar/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/oyunlar/"][$kategoriLen])-1;
	}
	#ucuncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/saglik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/saglik/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/saglik/"][$kategoriLen])-1;
	}
	#ucuncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sanat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/sanat/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/sanat/"][$kategoriLen])-1;
	}
	#ucuncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sinema/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/sinema/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/sinema/"][$kategoriLen])-1;
	}
	#ucuncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/siyaset/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/siyaset/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/siyaset/"][$kategoriLen])-1;
	}
	#ucuncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sozlukici/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/sozlukici/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/sozlukici/"][$kategoriLen])-1;
	}
	#ucuncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/spor/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/spor/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/spor/"][$kategoriLen])-1;
	}
	#ucuncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/tarih/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/tarih/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/tarih/"][$kategoriLen])-1;
	}
	#ucuncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/teknoloji/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/teknoloji/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/teknoloji/"][$kategoriLen])-1;
	}	
	#ucuncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/televizyon/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/televizyon/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/televizyon/"][$kategoriLen])-1;
	}
	#ucuncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yasam/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/yasam/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/yasam/"][$kategoriLen])-1;
	}
	#ucuncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yeme-icme/"]); $kategoriLen++){
		$enUzunTitle[$category_dict3["/kategori/yeme-icme/"][$kategoriLen][0]] = sizeOf($category_dict3["/kategori/yeme-icme/"][$kategoriLen])-1;
	}
	#dorduncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/anket/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/anket/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/anket/"][$kategoriLen])-1;
	}
	#dorduncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/bilim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/bilim/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/bilim/"][$kategoriLen])-1;
	}
	#dorduncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cinsellik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/cinsellik/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/cinsellik/"][$kategoriLen])-1;
	}	
	#dorduncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cografya-gezi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/cografya-gezi/"][$kategoriLen])-1;
	}
	#dorduncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/dini/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/dini/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/dini/"][$kategoriLen])-1;
	}
	#dorduncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/diziler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/diziler/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/diziler/"][$kategoriLen])-1;
	}
	#dorduncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/edebiyat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/edebiyat/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/edebiyat/"][$kategoriLen])-1;
	}
	#dorduncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/egitim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/egitim/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/egitim/"][$kategoriLen])-1;
	}
	#dorduncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/ekonomi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/ekonomi/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/ekonomi/"][$kategoriLen])-1;
	}
	#dorduncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/haber/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/haber/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/haber/"][$kategoriLen])-1;
	}
	#dorduncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/igrenc/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/igrenc/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/igrenc/"][$kategoriLen])-1;
	}
	#dorduncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/iliskiler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/iliskiler/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/iliskiler/"][$kategoriLen])-1;
	}
	#dorduncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/internet/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/internet/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/internet/"][$kategoriLen])-1;
	}
	#dorduncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/irkci/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/irkci/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/irkci/"][$kategoriLen])-1;
	}
	#dorduncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/moda/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/moda/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/moda/"][$kategoriLen])-1;
	}
	#dorduncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/muzik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/muzik/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/muzik/"][$kategoriLen])-1;
	}
	#dorduncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/nickalti/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/nickalti/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/nickalti/"][$kategoriLen])-1;
	}
	#dorduncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/oyunlar/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/oyunlar/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/oyunlar/"][$kategoriLen])-1;
	}
	#dorduncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/saglik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/saglik/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/saglik/"][$kategoriLen])-1;
	}
	#dorduncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sanat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/sanat/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/sanat/"][$kategoriLen])-1;
	}
	#dorduncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sinema/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/sinema/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/sinema/"][$kategoriLen])-1;
	}
	#dorduncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/siyaset/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/siyaset/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/siyaset/"][$kategoriLen])-1;
	}
	#dorduncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sozlukici/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/sozlukici/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/sozlukici/"][$kategoriLen])-1;
	}
	#dorduncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/spor/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/spor/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/spor/"][$kategoriLen])-1;
	}
	#dorduncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/tarih/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/tarih/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/tarih/"][$kategoriLen])-1;
	}
	#dorduncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/teknoloji/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/teknoloji/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/teknoloji/"][$kategoriLen])-1;
	}	
	#dorduncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/televizyon/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/televizyon/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/televizyon/"][$kategoriLen])-1;
	}
	#dorduncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yasam/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/yasam/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/yasam/"][$kategoriLen])-1;
	}
	#dorduncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yeme-icme/"]); $kategoriLen++){
		$enUzunTitle[$category_dict4["/kategori/yeme-icme/"][$kategoriLen][0]] = sizeOf($category_dict4["/kategori/yeme-icme/"][$kategoriLen])-1;
	}
	#besinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/anket/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/anket/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/anket/"][$kategoriLen])-1;
	}
	#besinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/bilim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/bilim/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/bilim/"][$kategoriLen])-1;
	}
	#besinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cinsellik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/cinsellik/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/cinsellik/"][$kategoriLen])-1;
	}	
	#besinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cografya-gezi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/cografya-gezi/"][$kategoriLen])-1;
	}
	#besinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/dini/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/dini/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/dini/"][$kategoriLen])-1;
	}
	#besinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/diziler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/diziler/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/diziler/"][$kategoriLen])-1;
	}
	#besinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/edebiyat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/edebiyat/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/edebiyat/"][$kategoriLen])-1;
	}
	#besinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/egitim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/egitim/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/egitim/"][$kategoriLen])-1;
	}
	#besinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/ekonomi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/ekonomi/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/ekonomi/"][$kategoriLen])-1;
	}
	#besinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/haber/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/haber/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/haber/"][$kategoriLen])-1;
	}
	#besinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/igrenc/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/igrenc/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/igrenc/"][$kategoriLen])-1;
	}
	#besinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/iliskiler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/iliskiler/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/iliskiler/"][$kategoriLen])-1;
	}
	#besinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/internet/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/internet/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/internet/"][$kategoriLen])-1;
	}
	#besinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/irkci/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/irkci/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/irkci/"][$kategoriLen])-1;
	}
	#besinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/moda/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/moda/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/moda/"][$kategoriLen])-1;
	}
	#besinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/muzik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/muzik/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/muzik/"][$kategoriLen])-1;
	}
	#besinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/nickalti/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/nickalti/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/nickalti/"][$kategoriLen])-1;
	}
	#besinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/oyunlar/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/oyunlar/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/oyunlar/"][$kategoriLen])-1;
	}
	#besinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/saglik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/saglik/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/saglik/"][$kategoriLen])-1;
	}
	#besinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sanat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/sanat/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/sanat/"][$kategoriLen])-1;
	}
	#besinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sinema/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/sinema/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/sinema/"][$kategoriLen])-1;
	}
	#besinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/siyaset/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/siyaset/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/siyaset/"][$kategoriLen])-1;
	}
	#besinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sozlukici/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/sozlukici/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/sozlukici/"][$kategoriLen])-1;
	}
	#besinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/spor/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/spor/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/spor/"][$kategoriLen])-1;
	}
	#besinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/tarih/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/tarih/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/tarih/"][$kategoriLen])-1;
	}
	#besinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/teknoloji/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/teknoloji/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/teknoloji/"][$kategoriLen])-1;
	}	
	#besinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/televizyon/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/televizyon/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/televizyon/"][$kategoriLen])-1;
	}
	#besinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yasam/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/yasam/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/yasam/"][$kategoriLen])-1;
	}
	#besinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yeme-icme/"]); $kategoriLen++){
		$enUzunTitle[$category_dict5["/kategori/yeme-icme/"][$kategoriLen][0]] = sizeOf($category_dict5["/kategori/yeme-icme/"][$kategoriLen])-1;
	}
	#altinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/anket/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/anket/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/anket/"][$kategoriLen])-1;
	}
	#altinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/bilim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/bilim/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/bilim/"][$kategoriLen])-1;
	}
	#altinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cinsellik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/cinsellik/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/cinsellik/"][$kategoriLen])-1;
	}	
	#altinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cografya-gezi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/cografya-gezi/"][$kategoriLen])-1;
	}
	#altinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/dini/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/dini/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/dini/"][$kategoriLen])-1;
	}
	#altinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/diziler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/diziler/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/diziler/"][$kategoriLen])-1;
	}
	#altinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/edebiyat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/edebiyat/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/edebiyat/"][$kategoriLen])-1;
	}
	#altinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/egitim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/egitim/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/egitim/"][$kategoriLen])-1;
	}
	#altinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/ekonomi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/ekonomi/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/ekonomi/"][$kategoriLen])-1;
	}
	#altinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/haber/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/haber/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/haber/"][$kategoriLen])-1;
	}
	#altinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/igrenc/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/igrenc/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/igrenc/"][$kategoriLen])-1;
	}
	#altinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/iliskiler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/iliskiler/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/iliskiler/"][$kategoriLen])-1;
	}
	#altinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/internet/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/internet/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/internet/"][$kategoriLen])-1;
	}
	#altinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/irkci/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/irkci/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/irkci/"][$kategoriLen])-1;
	}
	#altinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/moda/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/moda/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/moda/"][$kategoriLen])-1;
	}
	#altinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/muzik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/muzik/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/muzik/"][$kategoriLen])-1;
	}
	#altinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/nickalti/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/nickalti/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/nickalti/"][$kategoriLen])-1;
	}
	#altinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/oyunlar/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/oyunlar/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/oyunlar/"][$kategoriLen])-1;
	}
	#altinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/saglik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/saglik/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/saglik/"][$kategoriLen])-1;
	}
	#altinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sanat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/sanat/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/sanat/"][$kategoriLen])-1;
	}
	#altinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sinema/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/sinema/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/sinema/"][$kategoriLen])-1;
	}
	#altinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/siyaset/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/siyaset/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/siyaset/"][$kategoriLen])-1;
	}
	#altinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sozlukici/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/sozlukici/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/sozlukici/"][$kategoriLen])-1;
	}
	#altinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/spor/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/spor/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/spor/"][$kategoriLen])-1;
	}
	#altinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/tarih/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/tarih/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/tarih/"][$kategoriLen])-1;
	}
	#altinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/teknoloji/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/teknoloji/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/teknoloji/"][$kategoriLen])-1;
	}	
	#altinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/televizyon/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/televizyon/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/televizyon/"][$kategoriLen])-1;
	}
	#altinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yasam/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/yasam/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/yasam/"][$kategoriLen])-1;
	}
	#altinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yeme-icme/"]); $kategoriLen++){
		$enUzunTitle[$category_dict6["/kategori/yeme-icme/"][$kategoriLen][0]] = sizeOf($category_dict6["/kategori/yeme-icme/"][$kategoriLen])-1;
	}
	#yedinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/anket/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/anket/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/anket/"][$kategoriLen])-1;
	}
	#yedinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/bilim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/bilim/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/bilim/"][$kategoriLen])-1;
	}
	#yedinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cinsellik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/cinsellik/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/cinsellik/"][$kategoriLen])-1;
	}	
	#yedinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cografya-gezi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/cografya-gezi/"][$kategoriLen])-1;
	}
	#yedinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/dini/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/dini/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/dini/"][$kategoriLen])-1;
	}
	#yedinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/diziler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/diziler/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/diziler/"][$kategoriLen])-1;
	}
	#yedinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/edebiyat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/edebiyat/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/edebiyat/"][$kategoriLen])-1;
	}
	#yedinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/egitim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/egitim/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/egitim/"][$kategoriLen])-1;
	}
	#yedinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/ekonomi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/ekonomi/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/ekonomi/"][$kategoriLen])-1;
	}
	#yedinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/haber/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/haber/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/haber/"][$kategoriLen])-1;
	}
	#yedinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/igrenc/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/igrenc/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/igrenc/"][$kategoriLen])-1;
	}
	#yedinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/iliskiler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/iliskiler/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/iliskiler/"][$kategoriLen])-1;
	}
	#yedinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/internet/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/internet/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/internet/"][$kategoriLen])-1;
	}
	#yedinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/irkci/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/irkci/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/irkci/"][$kategoriLen])-1;
	}
	#yedinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/moda/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/moda/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/moda/"][$kategoriLen])-1;
	}
	#yedinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/muzik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/muzik/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/muzik/"][$kategoriLen])-1;
	}
	#yedinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/nickalti/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/nickalti/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/nickalti/"][$kategoriLen])-1;
	}
	#yedinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/oyunlar/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/oyunlar/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/oyunlar/"][$kategoriLen])-1;
	}
	#yedinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/saglik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/saglik/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/saglik/"][$kategoriLen])-1;
	}
	#yedinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sanat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/sanat/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/sanat/"][$kategoriLen])-1;
	}
	#yedinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sinema/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/sinema/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/sinema/"][$kategoriLen])-1;
	}
	#yedinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/siyaset/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/siyaset/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/siyaset/"][$kategoriLen])-1;
	}
	#yedinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sozlukici/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/sozlukici/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/sozlukici/"][$kategoriLen])-1;
	}
	#yedinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/spor/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/spor/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/spor/"][$kategoriLen])-1;
	}
	#yedinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/tarih/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/tarih/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/tarih/"][$kategoriLen])-1;
	}
	#yedinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/teknoloji/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/teknoloji/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/teknoloji/"][$kategoriLen])-1;
	}	
	#yedinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/televizyon/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/televizyon/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/televizyon/"][$kategoriLen])-1;
	}
	#yedinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yasam/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/yasam/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/yasam/"][$kategoriLen])-1;
	}
	#yedinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yeme-icme/"]); $kategoriLen++){
		$enUzunTitle[$category_dict7["/kategori/yeme-icme/"][$kategoriLen][0]] = sizeOf($category_dict7["/kategori/yeme-icme/"][$kategoriLen])-1;
	}
	#sekizinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/anket/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/anket/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/anket/"][$kategoriLen])-1;
	}
	#sekizinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/bilim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/bilim/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/bilim/"][$kategoriLen])-1;
	}
	#sekizinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cinsellik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/cinsellik/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/cinsellik/"][$kategoriLen])-1;
	}	
	#sekizinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cografya-gezi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/cografya-gezi/"][$kategoriLen])-1;
	}
	#sekizinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/dini/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/dini/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/dini/"][$kategoriLen])-1;
	}
	#sekizinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/diziler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/diziler/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/diziler/"][$kategoriLen])-1;
	}
	#sekizinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/edebiyat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/edebiyat/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/edebiyat/"][$kategoriLen])-1;
	}
	#sekizinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/egitim/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/egitim/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/egitim/"][$kategoriLen])-1;
	}
	#sekizinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/ekonomi/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/ekonomi/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/ekonomi/"][$kategoriLen])-1;
	}
	#sekizinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/haber/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/haber/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/haber/"][$kategoriLen])-1;
	}
	#sekizinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/igrenc/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/igrenc/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/igrenc/"][$kategoriLen])-1;
	}
	#sekizinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/iliskiler/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/iliskiler/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/iliskiler/"][$kategoriLen])-1;
	}
	#sekizinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/internet/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/internet/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/internet/"][$kategoriLen])-1;
	}
	#sekizinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/irkci/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/irkci/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/irkci/"][$kategoriLen])-1;
	}
	#sekizinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/moda/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/moda/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/moda/"][$kategoriLen])-1;
	}
	#sekizinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/muzik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/muzik/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/muzik/"][$kategoriLen])-1;
	}
	#sekizinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/nickalti/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/nickalti/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/nickalti/"][$kategoriLen])-1;
	}
	#sekizinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/oyunlar/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/oyunlar/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/oyunlar/"][$kategoriLen])-1;
	}
	#sekizinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/saglik/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/saglik/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/saglik/"][$kategoriLen])-1;
	}
	#sekizinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sanat/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/sanat/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/sanat/"][$kategoriLen])-1;
	}
	#sekizinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sinema/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/sinema/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/sinema/"][$kategoriLen])-1;
	}
	#sekizinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/siyaset/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/siyaset/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/siyaset/"][$kategoriLen])-1;
	}
	#sekizinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sozlukici/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/sozlukici/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/sozlukici/"][$kategoriLen])-1;
	}
	#sekizinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/spor/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/spor/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/spor/"][$kategoriLen])-1;
	}
	#sekizinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/tarih/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/tarih/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/tarih/"][$kategoriLen])-1;
	}
	#sekizinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/teknoloji/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/teknoloji/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/teknoloji/"][$kategoriLen])-1;
	}	
	#sekizinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/televizyon/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/televizyon/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/televizyon/"][$kategoriLen])-1;
	}
	#sekizinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yasam/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/yasam/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/yasam/"][$kategoriLen])-1;
	}
	#sekizinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yeme-icme/"]); $kategoriLen++){
		$enUzunTitle[$category_dict8["/kategori/yeme-icme/"][$kategoriLen][0]] = sizeOf($category_dict8["/kategori/yeme-icme/"][$kategoriLen])-1;
	}	
}
if ($selectOption == "En çok downvote alan entryler ve yazarları") {
	#ilk dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ilk dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
	#ilk dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ikinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
	#ikinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
		#ucuncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#ucuncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
	#ucuncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#dorduncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
	#dorduncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
		#besinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#besinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
	#besinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#altinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
	#altinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
		#yedinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#yedinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
	#yedinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}
	#sekizinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
	#sekizinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$downvote[$category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_dislikes"]);	
		}
	}	
}
if ($selectOption == "En çok upvote alan entryler ve yazarları") {
	#ilk dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
	#ilk dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ilk dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
	#ikinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ikinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
	#ucuncu dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#ucuncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
	#dorduncu dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#dorduncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
	#besinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#besinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
	#altinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#altinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
	#yedinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#yedinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/anket/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/dini/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/haber/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/internet/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
	#sekizinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/moda/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/spor/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}
	#sekizinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			$upvote[$category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["entry_text"]] = array($category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],(int)$category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["number_of_likes"]);	
		}
	}	
}
if ($selectOption == "En çok entry giren yazar") {
	#ilk dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/anket/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/dini/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ilk dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ilk dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/haber/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/internet/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ilk dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ilk dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/moda/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ilk dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ilk dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/spor/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ilk dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict1["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict1["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict1["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/anket/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/dini/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ikinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ikinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/haber/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/internet/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ikinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ikinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/moda/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ikinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ikinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/spor/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ikinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict2["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict2["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict2["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/anket/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/dini/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ucuncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ucuncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/haber/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/internet/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ucuncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ucuncu dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/moda/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ucuncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#ucuncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/spor/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#ucuncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict3["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict3["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict3["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/anket/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/dini/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#dorduncu dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#dorduncu dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/haber/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/internet/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#dorduncu dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#dorduncu dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/moda/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#dorduncu dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#dorduncu dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/spor/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#dorduncu dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict4["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict4["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict4["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/anket/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/dini/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#besinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#besinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/haber/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/internet/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#besinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#besinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/moda/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#besinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#besinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/spor/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#besinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict5["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict5["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict5["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/anket/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/dini/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#altinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#altinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/haber/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/internet/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#altinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#altinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/moda/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#altinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#altinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/spor/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#altinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict6["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict6["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict6["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/anket/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/dini/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#yedinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#yedinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/haber/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/internet/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#yedinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#yedinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/moda/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#yedinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#yedinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/spor/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#yedinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict7["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict7["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict7["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya anket
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/anket/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/anket/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/anket/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya bilim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/bilim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/bilim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/bilim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya cinsellik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cinsellik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/cinsellik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/cinsellik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya cografya-gezi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/cografya-gezi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/cografya-gezi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/cografya-gezi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya dini
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/dini/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/dini/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/dini/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya diziler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/diziler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/diziler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/diziler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya edebiyat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/edebiyat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/edebiyat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/edebiyat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#sekizinci dosya egitim
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/egitim/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/egitim/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/egitim/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#sekizinci dosya ekonomi
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/ekonomi/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/ekonomi/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/ekonomi/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya haber
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/haber/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/haber/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/haber/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya igrenc
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/igrenc/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/igrenc/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/igrenc/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya iliskiler
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/iliskiler/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/iliskiler/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/iliskiler/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya internet
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/internet/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/internet/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/internet/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#sekizinci dosya irkci
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/irkci/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/irkci/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/irkci/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#sekizinci dosya kategorisiz
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/kategorisiz/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/kategorisiz/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/kategorisiz/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya moda
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/moda/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/moda/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/moda/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#sekizinci dosya muzik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/muzik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/muzik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/muzik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya nickalti
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/nickalti/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/nickalti/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/nickalti/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya oyunlar
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/oyunlar/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/oyunlar/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/oyunlar/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya saglik
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/saglik/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/saglik/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/saglik/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya sanat
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sanat/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sanat/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/sanat/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya sinema
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sinema/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sinema/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/sinema/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya siyaset
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/siyaset/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/siyaset/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/siyaset/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
	#sekizinci dosya sozlukici
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/sozlukici/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/sozlukici/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/sozlukici/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya spor
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/spor/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/spor/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/spor/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya tarih
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/tarih/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/tarih/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/tarih/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya teknoloji
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/teknoloji/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/teknoloji/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/teknoloji/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya televizyon
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/televizyon/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/televizyon/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/televizyon/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya yasam
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yasam/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/yasam/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/yasam/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}
	#sekizinci dosya yeme-icme
	for($kategoriLen = 0; $kategoriLen<sizeOf($category_dict8["/kategori/yeme-icme/"]); $kategoriLen++){
		for($titleLen = 1; $titleLen<sizeOf($category_dict8["/kategori/yeme-icme/"][$kategoriLen]); $titleLen++){
			if(array_key_exists($category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"],$yazarlar)){
				$a = $yazarlar[$category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]];
				$yazarlar[$category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = ++$a;

			}
			else{
				$yazarlar[$category_dict8["/kategori/yeme-icme/"][$kategoriLen][$titleLen]["author"]] = 1 ;
			}
		}
	}	
}

echo "<br/>\n";
echo "<br/>\n";
$counter = 0;

#NUMBER OF DISLIKES
$sortarray = array();
foreach ($downvote as $key => $row){
    $sortarray[$key] = $row[1];
}

array_multisort($sortarray, SORT_ASC, $downvote);

foreach($downvote as $key => $val){

echo "Entry: $key<br/>\n Author: $val[0] Downvote: $val[1] <br/>\n";
echo "<br/>\n";
  $counter++;
  if($counter == $counterLimit && $counterLimit != 0){
	  $counter = 0;
	  break;
  }
}


#NUMBER OF LIKES
$sortarray = array();
foreach ($upvote as $key => $row){
    $sortarray[$key] = $row[1];
}

array_multisort($sortarray, SORT_DESC, $upvote);
foreach($upvote as $key => $val){
  echo "Entry: $key<br/>\n Author: $val[0] Upvote: $val[1] <br/>\n";
  echo "<br/>\n";
  $counter++;
  if($counter == $counterLimit && $counterLimit != 0){
	  $counter = 0;
	  break;
  }  
}

arsort($enUzunTitle);
#EN UZUN TITLE
foreach($enUzunTitle as $key => $val){
  echo "Title: $key<br/>\n Entry Count: $val<br/>\n";
  echo "<br/>\n";
  $counter++;
  if($counter == $counterLimit && $counterLimit != 0){
	  $counter = 0;
	  break;
  }
}

#EN COK ENTRY GIREN YAZAR
arsort($yazarlar);
foreach($yazarlar as $key => $val){
  echo "Author: $key<br/>\n Entry Count: $val<br/>\n";
  echo "<br/>\n";
  $counter++;
  if($counter == $counterLimit && $counterLimit != 0){
	  $counter = 0;
	  break;
  }
}
?>

</body>
</html>
