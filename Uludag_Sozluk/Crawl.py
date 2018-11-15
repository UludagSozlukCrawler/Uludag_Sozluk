import json
from urllib.request import urlopen
from bs4 import BeautifulSoup
from collections import defaultdict
from time import sleep
import datetime
from pathlib import Path

title_dict = defaultdict(list)
entry_dict = defaultdict(list)
category_dict = defaultdict(defaultdict)

title_dict_edit = defaultdict(list)
entry_dict_edit = defaultdict(list)
category_dict_edit = defaultdict(defaultdict)


def JsonDumper(dict):
    with open('data.json', 'w', encoding='utf-8') as outfile:
        json.dump(dict, outfile, sort_keys=True, indent=4, ensure_ascii=False)

def onExitJsonDumper(startIndex,dict):
    JsonDumper(dict)
    with open('onExitIndex.txt', 'w', encoding='utf-8') as outfile:
        json.dump(startIndex, outfile, sort_keys=True, indent=4, ensure_ascii=False)

def set_Limits():
    file = Path("onExitIndex.txt")
    if file.is_file():
        onExitIndex = open(file, "r")
        onRead = onExitIndex.read()
        infoArray = onRead.split(" ")

        startIndex = (infoArray[0])[1:]
        endIndex = (infoArray[1])[0:infoArray[1].__len__()-1]
        get_titles(int(startIndex)+1,int(endIndex))
    else:
        get_titles(39500100,39500200)

def get_titles(startIndex,endIndex):

    i=0
    try:
        for i in range(startIndex, endIndex):
            print(i)   #print current working index
            author = ""
            entry_date = datetime
            entry_time = datetime
            entry_text = ""
            number_of_likes = 0
            number_of_dislikes = 0
            title = ""
            date = ""
            time = ""
            category_name = ""

            quote_page = 'https://www.uludagsozluk.com/?id=' + str(i)
            soup = BeautifulSoup(urlopen(quote_page), 'html.parser')

            link = soup.find('a', attrs={'class':'entry-date'})     #take entry date
            if (link is not None):
                dateAndTime = link.getText().strip()
                dateAndTime = dateAndTime[int(dateAndTime.index(' ')):].strip()
                date = dateAndTime[0:int(dateAndTime.index(' '))]
                time = dateAndTime[int(dateAndTime.index(' '))+1:]

                dateArray = date.split('.')
                day = dateArray[0]
                month = dateArray[1]
                year = dateArray[2]

                if(datetime.datetime(int(year),int(month),int(day)) < (datetime.datetime(2018,1,1))):
                    continue

                timeArray = time.split(':')
                hour = timeArray[0]
                minute = timeArray[1]

                entry_date = datetime.date(int(year), int(month), int(day))
                entry_time = datetime.time(int(hour), int(minute))

            link = soup.find('h1', attrs={'class':'tekentry-baslik'})   #take entry name
            if (link is not None):
                title = link.getText().strip()

            link = soup.find('div', attrs={'class':'entry-p'})  #take entry text
            if (link is not None):
                entry_text = link.getText().strip()

            link = soup.find('a', attrs={'href':'#'}) #take entry author
            if (link is not None):
                author = link.getText().strip()

            link = soup.find('span', attrs={'class':'oylar arti_sayi color-yesil'})     #take entry upvote
            if (link is not None):
                number_of_likes = link.getText().strip()

            if (number_of_likes == "" or number_of_likes == 0):
                number_of_likes = "0"

            link = soup.find('span', attrs={'class':'oylar eksi_sayi '})     #take entry downvote
            if (link is not None):
                number_of_dislikes = link.getText().strip()

            if (number_of_dislikes == ""):
                number_of_dislikes = "0"

                link = soup.find('ul', attrs={'class': 'breadcrumb'})  # take entry category name
            if (link is not None):
                for index_list in link.find_all('a'):
                    if index_list.has_attr('href'):
                        category_name = index_list.attrs['href']

            if (category_name == ""):
                category_name = "/kategori/kategorisiz/"

            entry_dict = {
                'author' : author,
                'entry_date' : date,
                'entry_time' : time,
                'entry_text' : entry_text,
                'number_of_likes' : number_of_likes,
                'number_of_dislikes' : number_of_dislikes,
            }

            if( not (author == "" and
            entry_text == "" and
            title == "" and
            date == "" and
            time == "")):
                if (title not in title_dict):
                    title_dict[title] = [title]

                title_dict[title].append(entry_dict)


                if "/kategori/" in category_name:
                    if(category_name not in category_dict):
                        category_dict[category_name] = []

                    if(title_dict[title] not in category_dict[category_name]):
                        category_dict[category_name].append(title_dict.get(title))

            onExitOutput = str(i+1) + " " + str(endIndex)
            onExitJsonDumper(onExitOutput,category_dict)

    except:
        print("Hata.")
        print(i)
        sleep(1)
        startIndex = i+1;
        get_titles(startIndex,endIndex)


    JsonDumper(category_dict)   #json writer function

def findEdits():

    with open('data.json', 'r', encoding='utf-8') as outfile:
        text = outfile.read()
        json_data = json.loads(text)

        if("/kategori/anket/" in json_data):
            for j in range(0,len(json_data["/kategori/anket/"])):
                title = json_data["/kategori/anket/"][j][0]
                category_name_edit = "/kategori/anket/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i  in range(1,len(json_data["/kategori/anket/"][0])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/anket/"][j][i]["author"],
                        'entry_date': json_data["/kategori/anket/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/anket/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/anket/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/anket/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/anket/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if(category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/bilim/" in json_data):
            for j in range(0,len(json_data["/kategori/bilim/"])):

                title = json_data["/kategori/bilim/"][j][0]
                category_name_edit = "/kategori/bilim/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/bilim/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/bilim/"][j][i]["author"],
                        'entry_date': json_data["/kategori/bilim/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/bilim/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/bilim/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/bilim/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/bilim/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/cinsellik/" in json_data):
            for j in range(0,len(json_data["/kategori/cinsellik/"])):

                title = json_data["/kategori/cinsellik/"][j][0]
                category_name_edit = "/kategori/cinsellik/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/cinsellik/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/cinsellik/"][j][i]["author"],
                        'entry_date': json_data["/kategori/cinsellik/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/cinsellik/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/cinsellik/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/cinsellik/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/cinsellik/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []
                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))


        if ("/kategori/dini/" in json_data):
            for j in range(0,len(json_data["/kategori/dini/"])):

                title = json_data["/kategori/dini/"][j][0]
                category_name_edit = "/kategori/dini/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/dini/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/dini/"][j][i]["author"],
                        'entry_date': json_data["/kategori/dini/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/dini/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/dini/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/dini/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/dini/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))


        if ("/kategori/cografya-gezi/" in json_data):
            for j in range(0,len(json_data["/kategori/cografya-gezi/"])):
                title = json_data["/kategori/cografya-gezi/"][j][0]
                category_name_edit = "/kategori/cografya-gezi/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/cografya-gezi/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/cografya-gezi/"][j][i]["author"],
                        'entry_date': json_data["/kategori/cografya-gezi/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/cografya-gezi/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/cografya-gezi/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/cografya-gezi/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/cografya-gezi/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/diziler/" in json_data):
            for j in range(0, len(json_data["/kategori/diziler/"])):

                title = json_data["/kategori/diziler/"][j][0]
                category_name_edit = "/kategori/diziler/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/diziler/"][j])):
                    entry_dict_edit = {
                        'author': json_data["/kategori/diziler/"][j][i]["author"],
                        'entry_date': json_data["/kategori/diziler/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/diziler/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/diziler/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/diziler/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/diziler/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/edebiyat/" in json_data):
            for j in range(0,len(json_data["/kategori/edebiyat/"])):

                title = json_data["/kategori/edebiyat/"][j][0]
                category_name_edit = "/kategori/edebiyat/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/edebiyat/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/edebiyat/"][j][i]["author"],
                        'entry_date': json_data["/kategori/edebiyat/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/edebiyat/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/edebiyat/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/edebiyat/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/edebiyat/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/egitim/" in json_data):
            for j in range(0,len(json_data["/kategori/egitim/"])):

                title = json_data["/kategori/egitim/"][j][0]
                category_name_edit = "/kategori/egitim/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/egitim/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/egitim/"][j][i]["author"],
                        'entry_date': json_data["/kategori/egitim/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/egitim/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/egitim/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/egitim/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/egitim/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/ekonomi/" in json_data):
            for j in range(0,len(json_data["/kategori/ekonomi/"])):

                title = json_data["/kategori/ekonomi/"][j][0]
                category_name_edit = "/kategori/ekonomi/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/ekonomi/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/ekonomi/"][j][i]["author"],
                        'entry_date': json_data["/kategori/ekonomi/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/ekonomi/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/ekonomi/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/ekonomi/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/ekonomi/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/haber/" in json_data):
            for j in range(0,len(json_data["/kategori/haber/"])):

                title = json_data["/kategori/haber/"][j][0]
                category_name_edit = "/kategori/haber/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/haber/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/haber/"][j][i]["author"],
                        'entry_date': json_data["/kategori/haber/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/haber/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/haber/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/haber/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/haber/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))


        if ("/kategori/igrenc/" in json_data):
            for j in range(0,len(json_data["/kategori/igrenc/"])):

                title = json_data["/kategori/igrenc/"][j][0]
                category_name_edit = "/kategori/igrenc/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/igrenc/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/igrenc/"][j][i]["author"],
                        'entry_date': json_data["/kategori/igrenc/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/igrenc/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/igrenc/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/igrenc/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/igrenc/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/iliskiler/" in json_data):
            for j in range(0,len(json_data["/kategori/iliskiler/"])):

                title = json_data["/kategori/iliskiler/"][j][0]
                category_name_edit = "/kategori/iliskiler/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/iliskiler/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/iliskiler/"][j][i]["author"],
                        'entry_date': json_data["/kategori/iliskiler/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/iliskiler/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/iliskiler/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/iliskiler/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/iliskiler/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/internet/" in json_data):
            for j in range(0,len(json_data["/kategori/internet/"])):

                title = json_data["/kategori/internet/"][j][0]
                category_name_edit = "/kategori/internet/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/internet/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/internet/"][j][i]["author"],
                        'entry_date': json_data["/kategori/internet/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/internet/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/internet/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/internet/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/internet/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/irkci/" in json_data):
            for j in range(0,len(json_data["/kategori/irkci/"])):

                title = json_data["/kategori/irkci/"][j][0]
                category_name_edit = "/kategori/irkci/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/irkci/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/irkci/"][j][i]["author"],
                        'entry_date': json_data["/kategori/irkci/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/irkci/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/irkci/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/irkci/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/irkci/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/kategorisiz/" in json_data):
            for j in range(0,len(json_data["/kategori/kategorisiz/"])):
                title = json_data["/kategori/kategorisiz/"][j][0]
                category_name_edit = "/kategori/kategorisiz/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/kategorisiz/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/kategorisiz/"][j][i]["author"],
                        'entry_date': json_data["/kategori/kategorisiz/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/kategorisiz/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/kategorisiz/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/kategorisiz/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/kategorisiz/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/moda/" in json_data):
            for j in range(0,len(json_data["/kategori/moda/"])):

                title = json_data["/kategori/moda/"][j][0]
                category_name_edit = "/kategori/moda/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/moda/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/moda/"][j][i]["author"],
                        'entry_date': json_data["/kategori/moda/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/moda/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/moda/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/moda/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/moda/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/muzik/" in json_data):
            for j in range(0,len(json_data["/kategori/muzik/"])):

                title = json_data["/kategori/muzik/"][j][0]
                category_name_edit = "/kategori/muzik/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/muzik/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/muzik/"][j][i]["author"],
                        'entry_date': json_data["/kategori/muzik/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/muzik/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/muzik/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/muzik/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/muzik/"][j][i]["number_of_dislikes"],
                    }
                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)

                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/nickalti/" in json_data):
            for j in range(0,len(json_data["/kategori/nickalti/"])):

                title = json_data["/kategori/nickalti/"][j][0]
                category_name_edit = "/kategori/nickalti/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/nickalti/"][0])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/nickalti/"][j][i]["author"],
                        'entry_date': json_data["/kategori/nickalti/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/nickalti/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/nickalti/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/nickalti/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/nickalti/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/oyunlar/" in json_data):
            for j in range(0,len(json_data["/kategori/oyunlar/"])):

                title = json_data["/kategori/oyunlar/"][j][0]
                category_name_edit = "/kategori/oyunlar/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/oyunlar/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/oyunlar/"][j][i]["author"],
                        'entry_date': json_data["/kategori/oyunlar/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/oyunlar/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/oyunlar/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/oyunlar/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/oyunlar/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/saglik/" in json_data):
            for j in range(0,len(json_data["/kategori/saglik/"])):

                title = json_data["/kategori/saglik/"][j][0]
                category_name_edit = "/kategori/saglik/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/saglik/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/saglik/"][j][i]["author"],
                        'entry_date': json_data["/kategori/saglik/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/saglik/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/saglik/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/saglik/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/saglik/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/sanat/" in json_data):
            for j in range(0,len(json_data["/kategori/sanat/"])):

                title = json_data["/kategori/sanat/"][j][0]
                category_name_edit = "/kategori/sanat/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/sanat/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/sanat/"][j][i]["author"],
                        'entry_date': json_data["/kategori/sanat/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/sanat/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/sanat/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/sanat/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/sanat/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/sinema/" in json_data):
            for j in range(0,len(json_data["/kategori/sinema/"])):

                title = json_data["/kategori/sinema/"][j][0]
                category_name_edit = "/kategori/sinema/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/sinema/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/sinema/"][j][i]["author"],
                        'entry_date': json_data["/kategori/sinema/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/sinema/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/sinema/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/sinema/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/sinema/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/siyaset/" in json_data):
            for j in range(0,len(json_data["/kategori/siyaset/"])):

                title = json_data["/kategori/siyaset/"][j][0]
                category_name_edit = "/kategori/siyaset/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/siyaset/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/siyaset/"][j][i]["author"],
                        'entry_date': json_data["/kategori/siyaset/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/siyaset/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/siyaset/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/siyaset/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/siyaset/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/sozlukici/" in json_data):
            for j in range(0,len(json_data["/kategori/sozlukici/"])):

                title = json_data["/kategori/sozlukici/"][j][0]
                category_name_edit = "/kategori/sozlukici/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/sozlukici/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/sozlukici/"][j][i]["author"],
                        'entry_date': json_data["/kategori/sozlukici/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/sozlukici/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/sozlukici/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/sozlukici/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/sozlukici/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/spor/" in json_data):
            for j in range(0,len(json_data["/kategori/spor/"])):

                title = json_data["/kategori/spor/"][j][0]
                category_name_edit = "/kategori/spor/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/spor/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/spor/"][j][i]["author"],
                        'entry_date': json_data["/kategori/spor/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/spor/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/spor/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/spor/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/spor/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/tarih/" in json_data):
            for j in range(0,len(json_data["/kategori/tarih/"])):

                title = json_data["/kategori/tarih/"][j][0]
                category_name_edit = "/kategori/tarih/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/tarih/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/tarih/"][j][i]["author"],
                        'entry_date': json_data["/kategori/tarih/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/tarih/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/tarih/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/tarih/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/tarih/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/teknoloji/" in json_data):
            for j in range(0,len(json_data["/kategori/teknoloji/"])):

                title = json_data["/kategori/teknoloji/"][j][0]
                category_name_edit = "/kategori/teknoloji/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/teknoloji/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/teknoloji/"][j][i]["author"],
                        'entry_date': json_data["/kategori/teknoloji/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/teknoloji/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/teknoloji/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/teknoloji/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/teknoloji/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/televizyon/" in json_data):
            for j in range(0,len(json_data["/kategori/televizyon/"])):

                title = json_data["/kategori/televizyon/"][j][0]
                category_name_edit = "/kategori/televizyon/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/televizyon/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/televizyon/"][j][i]["author"],
                        'entry_date': json_data["/kategori/televizyon/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/televizyon/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/televizyon/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/televizyon/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/televizyon/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))


        if ("/kategori/yasam/" in json_data):
            for j in range(0,len(json_data["/kategori/yasam/"])):

                title = json_data["/kategori/yasam/"][j][0]
                category_name_edit = "/kategori/yasam/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/yasam/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/yasam/"][j][i]["author"],
                        'entry_date': json_data["/kategori/yasam/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/yasam/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/yasam/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/yasam/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/yasam/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

        if ("/kategori/yeme-icme/" in json_data):
            for j in range(0,len(json_data["/kategori/yeme-icme/"])):

                title = json_data["/kategori/yeme-icme/"][j][0]
                category_name_edit = "/kategori/yeme-icme/"
                if (title not in title_dict_edit):
                    title_dict_edit[title] = [title]
                for i in range(1, len(json_data["/kategori/yeme-icme/"][j])):

                    entry_dict_edit = {
                        'author': json_data["/kategori/yeme-icme/"][j][i]["author"],
                        'entry_date': json_data["/kategori/yeme-icme/"][j][i]["entry_date"],
                        'entry_time': json_data["/kategori/yeme-icme/"][j][i]["entry_time"],
                        'entry_text': json_data["/kategori/yeme-icme/"][j][i]["entry_text"],
                        'number_of_likes': json_data["/kategori/yeme-icme/"][j][i]["number_of_likes"],
                        'number_of_dislikes': json_data["/kategori/yeme-icme/"][j][i]["number_of_dislikes"],
                    }

                    if(entry_dict_edit not in title_dict_edit[title]):
                        title_dict_edit[title].append(entry_dict_edit)
                    if (category_name_edit not in category_dict_edit):
                        category_dict_edit[category_name_edit] = []

                    if(title_dict_edit[title] not in category_dict_edit[category_name_edit]):
                        category_dict_edit[category_name_edit].append(title_dict_edit.get(title))

def editCrawler(startIndex,endIndex):

    i=0
    try:
        for i in range(startIndex, endIndex):
            print(i)   #print current working index
            author = ""
            entry_date = datetime
            entry_time = datetime
            entry_text = ""
            number_of_likes = 0
            number_of_dislikes = 0
            title = ""
            date = ""
            time = ""
            category_name = ""

            quote_page = 'https://www.uludagsozluk.com/?id=' + str(i)
            soup = BeautifulSoup(urlopen(quote_page), 'html.parser')

            link = soup.find('a', attrs={'class':'entry-date'})     #take entry date
            if (link is not None):
                dateAndTime = link.getText().strip()
                dateAndTime = dateAndTime[int(dateAndTime.index(' ')):].strip()
                date = dateAndTime[0:int(dateAndTime.index(' '))]
                time = dateAndTime[int(dateAndTime.index(' '))+1:]

                dateArray = date.split('.')
                day = dateArray[0]
                month = dateArray[1]
                year = dateArray[2]

                if(datetime.datetime(int(year),int(month),int(day)) < (datetime.datetime(2018,1,1))):
                    continue

                timeArray = time.split(':')
                hour = timeArray[0]
                minute = timeArray[1]

                entry_date = datetime.date(int(year), int(month), int(day))
                entry_time = datetime.time(int(hour), int(minute))

            link = soup.find('h1', attrs={'class':'tekentry-baslik'})   #take entry name
            if (link is not None):
                title = link.getText().strip()

            link = soup.find('div', attrs={'class':'entry-p'})  #take entry text
            if (link is not None):
                entry_text = link.getText().strip()

            link = soup.find('a', attrs={'href':'#'}) #take entry author
            if (link is not None):
                author = link.getText().strip()

            link = soup.find('span', attrs={'class':'oylar arti_sayi color-yesil'})     #take entry upvote
            if (link is not None):
                number_of_likes = link.getText().strip()

            if (number_of_likes == "" or number_of_likes == 0):
                number_of_likes = "0"

            link = soup.find('span', attrs={'class':'oylar eksi_sayi '})     #take entry downvote
            if (link is not None):
                number_of_dislikes = link.getText().strip()

            if (number_of_dislikes == ""):
                number_of_dislikes = "0"

                link = soup.find('ul', attrs={'class': 'breadcrumb'})  # take entry category name
            if (link is not None):
                for index_list in link.find_all('a'):
                    if index_list.has_attr('href'):
                        category_name = index_list.attrs['href']

            if (category_name == ""):
                category_name = "/kategori/kategorisiz/"

            entry_dict = {
                'author' : author,
                'entry_date' : date,
                'entry_time' : time,
                'entry_text' : entry_text,
                'number_of_likes' : number_of_likes,
                'number_of_dislikes' : number_of_dislikes,
            }

            if( not (author == "" and
            entry_text == "" and
            title == "" and
            date == "" and
            time == "")):
                if (title not in title_dict):
                    title_dict[title] = [title]

                title_dict[title].append(entry_dict)


                if "/kategori/" in category_name:
                    if(category_name not in category_dict):
                        category_dict[category_name] = []

                    if(title_dict[title] not in category_dict[category_name]):
                        category_dict[category_name].append(title_dict.get(title))
    except:
        print("Hata.")
        print(i)
        sleep(1)
        startIndex = i+1;
        get_titles(startIndex,endIndex)

def comparer():

    editCrawler(39500100,39500200)
    findEdits()

    if ("/kategori/anket/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/anket/"])):
            for i in range(1,len(category_dict_edit["/kategori/anket/"][j])):
                if(category_dict_edit["/kategori/anket/"][j][i]["entry_date"] != category_dict["/kategori/anket/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/anket/"][j][i]["entry_text"] = category_dict["/kategori/anket/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/anket/"][j][i]["entry_date"] = category_dict["/kategori/anket/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/anket/"][j][i]["number_of_likes"] != category_dict["/kategori/anket/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/anket/"][j][i]["number_of_likes"] = category_dict["/kategori/anket/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/anket/"][j][i]["number_of_dislikes"] != category_dict["/kategori/anket/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/anket/"][j][i]["number_of_dislikes"] = category_dict["/kategori/anket/"][j][i]["number_of_dislikes"]

    if ("/kategori/bilim/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/bilim/"])):

            for i in range(1,len(category_dict_edit["/kategori/bilim/"][j])):
                if(category_dict_edit["/kategori/bilim/"][j][i]["entry_date"] != category_dict["/kategori/bilim/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/bilim/"][j][i]["entry_text"] = category_dict["/kategori/bilim/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/bilim/"][j][i]["entry_date"] = category_dict["/kategori/bilim/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/bilim/"][j][i]["number_of_likes"] != category_dict["/kategori/bilim/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/bilim/"][j][i]["number_of_likes"] = category_dict["/kategori/bilim/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/bilim/"][j][i]["number_of_dislikes"] != category_dict["/kategori/bilim/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/bilim/"][j][i]["number_of_dislikes"] = category_dict["/kategori/bilim/"][j][i]["number_of_dislikes"]

    if ("/kategori/cografya-gezi/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/cografya-gezi/"])):

            for i in range(1,len(category_dict_edit["/kategori/cografya-gezi/"][j])):
                if(category_dict_edit["/kategori/cografya-gezi/"][j][i]["entry_date"] != category_dict["/kategori/cografya-gezi/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/cografya-gezi/"][j][i]["entry_text"] = category_dict["/kategori/cografya-gezi/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/cografya-gezi/"][j][i]["entry_date"] = category_dict["/kategori/cografya-gezi/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/cografya-gezi/"][j][i]["number_of_likes"] != category_dict["/kategori/cografya-gezi/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/cografya-gezi/"][j][i]["number_of_likes"] = category_dict["/kategori/cografya-gezi/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/cografya-gezi/"][j][i]["number_of_dislikes"] != category_dict["/kategori/cografya-gezi/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/cografya-gezi/"][j][i]["number_of_dislikes"] = category_dict["/kategori/cografya-gezi/"][j][i]["number_of_dislikes"]

    if ("/kategori/cinsellik/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/cinsellik/"])):

            for i in range(1,len(category_dict_edit["/kategori/cinsellik/"][j])):
                if(category_dict_edit["/kategori/cinsellik/"][j][i]["entry_date"] != category_dict["/kategori/cinsellik/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/cinsellik/"][j][i]["entry_text"] = category_dict["/kategori/cinsellik/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/cinsellik/"][j][i]["entry_date"] = category_dict["/kategori/cinsellik/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/cinsellik/"][j][i]["number_of_likes"] != category_dict["/kategori/cinsellik/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/cinsellik/"][j][i]["number_of_likes"] = category_dict["/kategori/cinsellik/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/cinsellik/"][j][i]["number_of_dislikes"] != category_dict["/kategori/cinsellik/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/cinsellik/"][j][i]["number_of_dislikes"] = category_dict["/kategori/cinsellik/"][j][i]["number_of_dislikes"]

    if ("/kategori/dini/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/dini/"])):

            for i in range(1,len(category_dict_edit["/kategori/dini/"][j])):
                if(category_dict_edit["/kategori/dini/"][j][i]["entry_date"] != category_dict["/kategori/dini/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/dini/"][j][i]["entry_text"] = category_dict["/kategori/dini/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/dini/"][j][i]["entry_date"] = category_dict["/kategori/dini/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/dini/"][j][i]["number_of_likes"] != category_dict["/kategori/dini/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/dini/"][j][i]["number_of_likes"] = category_dict["/kategori/dini/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/dini/"][j][i]["number_of_dislikes"] != category_dict["/kategori/dini/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/dini/"][j][i]["number_of_dislikes"] = category_dict["/kategori/dini/"][j][i]["number_of_dislikes"]

    if ("/kategori/diziler/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/diziler/"])):

            for i in range(1,len(category_dict_edit["/kategori/diziler/"][j])):
                if(category_dict_edit["/kategori/diziler/"][j][i]["entry_date"] != category_dict["/kategori/diziler/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/diziler/"][j][i]["entry_text"] = category_dict["/kategori/diziler/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/diziler/"][j][i]["entry_date"] = category_dict["/kategori/diziler/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/diziler/"][j][i]["number_of_likes"] != category_dict["/kategori/diziler/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/diziler/"][j][i]["number_of_likes"] = category_dict["/kategori/diziler/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/diziler/"][j][i]["number_of_dislikes"] != category_dict["/kategori/diziler/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/diziler/"][j][i]["number_of_dislikes"] = category_dict["/kategori/diziler/"][j][i]["number_of_dislikes"]

    if ("/kategori/edebiyat/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/edebiyat/"])):

            for i in range(1,len(category_dict_edit["/kategori/edebiyat/"][j])):
                if(category_dict_edit["/kategori/edebiyat/"][j][i]["entry_date"] != category_dict["/kategori/edebiyat/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/edebiyat/"][j][i]["entry_text"] = category_dict["/kategori/edebiyat/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/edebiyat/"][j][i]["entry_date"] = category_dict["/kategori/edebiyat/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/edebiyat/"][j][i]["number_of_likes"] != category_dict["/kategori/edebiyat/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/edebiyat/"][j][i]["number_of_likes"] = category_dict["/kategori/edebiyat/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/edebiyat/"][j][i]["number_of_dislikes"] != category_dict["/kategori/edebiyat/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/edebiyat/"][j][i]["number_of_dislikes"] = category_dict["/kategori/edebiyat/"][j][i]["number_of_dislikes"]

    if ("/kategori/egitim/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/egitim/"])):

            for i in range(1,len(category_dict_edit["/kategori/egitim/"][0])):
                if(category_dict_edit["/kategori/egitim/"][j][i]["entry_date"] != category_dict["/kategori/egitim/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/egitim/"][j][i]["entry_text"] = category_dict["/kategori/egitim/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/egitim/"][j][i]["entry_date"] = category_dict["/kategori/egitim/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/egitim/"][j][i]["number_of_likes"] != category_dict["/kategori/egitim/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/egitim/"][j][i]["number_of_likes"] = category_dict["/kategori/egitim/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/egitim/"][j][i]["number_of_dislikes"] != category_dict["/kategori/egitim/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/egitim/"][j][i]["number_of_dislikes"] = category_dict["/kategori/egitim/"][j][i]["number_of_dislikes"]

    if ("/kategori/ekonomi/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/ekonomi/"])):

            for i in range(1,len(category_dict_edit["/kategori/ekonomi/"][j])):
                if(category_dict_edit["/kategori/ekonomi/"][j][i]["entry_date"] != category_dict["/kategori/ekonomi/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/ekonomi/"][j][i]["entry_text"] = category_dict["/kategori/ekonomi/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/ekonomi/"][j][i]["entry_date"] = category_dict["/kategori/ekonomi/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/ekonomi/"][j][i]["number_of_likes"] != category_dict["/kategori/ekonomi/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/ekonomi/"][j][i]["number_of_likes"] = category_dict["/kategori/ekonomi/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/ekonomi/"][j][i]["number_of_dislikes"] != category_dict["/kategori/ekonomi/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/ekonomi/"][j][i]["number_of_dislikes"] = category_dict["/kategori/ekonomi/"][j][i]["number_of_dislikes"]

    if ("/kategori/haber/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/haber/"])):

            for i in range(1,len(category_dict_edit["/kategori/haber/"][j])):
                if(category_dict_edit["/kategori/haber/"][j][i]["entry_date"] != category_dict["/kategori/haber/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/haber/"][j][i]["entry_text"] = category_dict["/kategori/haber/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/haber/"][j][i]["entry_date"] = category_dict["/kategori/haber/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/haber/"][j][i]["number_of_likes"] != category_dict["/kategori/haber/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/haber/"][j][i]["number_of_likes"] = category_dict["/kategori/haber/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/haber/"][j][i]["number_of_dislikes"] != category_dict["/kategori/haber/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/haber/"][j][i]["number_of_dislikes"] = category_dict["/kategori/haber/"][j][i]["number_of_dislikes"]

    if ("/kategori/igrenc/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/igrenc/"])):

            for i in range(1,len(category_dict_edit["/kategori/igrenc/"][j])):
                if(category_dict_edit["/kategori/igrenc/"][j][i]["entry_date"] != category_dict["/kategori/igrenc/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/igrenc/"][j][i]["entry_text"] = category_dict["/kategori/igrenc/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/igrenc/"][j][i]["entry_date"] = category_dict["/kategori/igrenc/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/igrenc/"][j][i]["number_of_likes"] != category_dict["/kategori/igrenc/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/igrenc/"][j][i]["number_of_likes"] = category_dict["/kategori/igrenc/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/igrenc/"][j][i]["number_of_dislikes"] != category_dict["/kategori/igrenc/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/igrenc/"][j][i]["number_of_dislikes"] = category_dict["/kategori/igrenc/"][j][i]["number_of_dislikes"]

    if ("/kategori/iliskiler/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/iliskiler/"])):

            for i in range(1,len(category_dict_edit["/kategori/iliskiler/"][j])):
                if(category_dict_edit["/kategori/iliskiler/"][j][i]["entry_date"] != category_dict["/kategori/iliskiler/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/iliskiler/"][j][i]["entry_text"] = category_dict["/kategori/iliskiler/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/iliskiler/"][j][i]["entry_date"] = category_dict["/kategori/iliskiler/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/iliskiler/"][j][i]["number_of_likes"] != category_dict["/kategori/iliskiler/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/iliskiler/"][j][i]["number_of_likes"] = category_dict["/kategori/iliskiler/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/iliskiler/"][j][i]["number_of_dislikes"] != category_dict["/kategori/iliskiler/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/iliskiler/"][j][i]["number_of_dislikes"] = category_dict["/kategori/iliskiler/"][j][i]["number_of_dislikes"]

    if ("/kategori/internet/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/dini/"])):

            for i in range(1,len(category_dict_edit["/kategori/internet/"][j])):
                if(category_dict_edit["/kategori/internet/"][j][i]["entry_date"] != category_dict["/kategori/internet/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/internet/"][j][i]["entry_text"] = category_dict["/kategori/internet/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/internet/"][j][i]["entry_date"] = category_dict["/kategori/internet/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/internet/"][j][i]["number_of_likes"] != category_dict["/kategori/internet/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/internet/"][j][i]["number_of_likes"] = category_dict["/kategori/internet/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/internet/"][j][i]["number_of_dislikes"] != category_dict["/kategori/internet/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/internet/"][j][i]["number_of_dislikes"] = category_dict["/kategori/internet/"][j][i]["number_of_dislikes"]

    if ("/kategori/irkci/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/irkci/"])):

            for i in range(1,len(category_dict_edit["/kategori/irkci/"][j])):
                if(category_dict_edit["/kategori/irkci/"][j][i]["entry_date"] != category_dict["/kategori/irkci/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/irkci/"][j][i]["entry_text"] = category_dict["/kategori/irkci/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/irkci/"][j][i]["entry_date"] = category_dict["/kategori/irkci/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/irkci/"][j][i]["number_of_likes"] != category_dict["/kategori/irkci/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/irkci/"][j][i]["number_of_likes"] = category_dict["/kategori/irkci/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/irkci/"][j][i]["number_of_dislikes"] != category_dict["/kategori/irkci/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/irkci/"][j][i]["number_of_dislikes"] = category_dict["/kategori/irkci/"][j][i]["number_of_dislikes"]

    if ("/kategori/kategorisiz/" in category_dict_edit):

        for j in range(0,len(category_dict_edit["/kategori/kategorisiz/"])):

            for i in range(1,len(category_dict_edit["/kategori/kategorisiz/"][j])):
                if(category_dict_edit["/kategori/kategorisiz/"][j][i]["entry_date"] != category_dict["/kategori/kategorisiz/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/kategorisiz/"][j][i]["entry_text"] = category_dict["/kategori/kategorisiz/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/kategorisiz/"][j][i]["entry_date"] = category_dict["/kategori/kategorisiz/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/kategorisiz/"][j][i]["number_of_likes"] != category_dict["/kategori/kategorisiz/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/kategorisiz/"][j][i]["number_of_likes"] = category_dict["/kategori/kategorisiz/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/kategorisiz/"][j][i]["number_of_dislikes"] != category_dict["/kategori/kategorisiz/"][j][i]["number_of_dislikes"]):

                    category_dict_edit["/kategori/kategorisiz/"][j][i]["number_of_dislikes"] = category_dict["/kategori/kategorisiz/"][j][i]["number_of_dislikes"]

    if ("/kategori/moda/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/moda/"])):

            for i in range(1,len(category_dict_edit["/kategori/moda/"][j])):
                if(category_dict_edit["/kategori/moda/"][j][i]["entry_date"] != category_dict["/kategori/moda/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/moda/"][j][i]["entry_text"] = category_dict["/kategori/moda/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/moda/"][j][i]["entry_date"] = category_dict["/kategori/moda/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/moda/"][j][i]["number_of_likes"] != category_dict["/kategori/moda/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/moda/"][j][i]["number_of_likes"] = category_dict["/kategori/moda/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/moda/"][j][i]["number_of_dislikes"] != category_dict["/kategori/moda/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/moda/"][j][i]["number_of_dislikes"] = category_dict["/kategori/moda/"][j][i]["number_of_dislikes"]

    if ("/kategori/muzik/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/muzik/"])):

            for i in range(1,len(category_dict_edit["/kategori/muzik/"][j])):
                if(category_dict_edit["/kategori/muzik/"][j][i]["entry_date"] != category_dict["/kategori/muzik/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/muzik/"][j][i]["entry_text"] = category_dict["/kategori/muzik/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/muzik/"][j][i]["entry_date"] = category_dict["/kategori/muzik/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/muzik/"][j][i]["number_of_likes"] != category_dict["/kategori/muzik/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/muzik/"][j][i]["number_of_likes"] = category_dict["/kategori/muzik/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/muzik/"][j][i]["number_of_dislikes"] != category_dict["/kategori/muzik/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/muzik/"][j][i]["number_of_dislikes"] = category_dict["/kategori/muzik/"][j][i]["number_of_dislikes"]

    if ("/kategori/nickalti/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/nickalti/"])):

            for i in range(1,len(category_dict_edit["/kategori/nickalti/"][j])):
                if(category_dict_edit["/kategori/nickalti/"][j][i]["entry_date"] != category_dict["/kategori/nickalti/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/nickalti/"][j][i]["entry_text"] = category_dict["/kategori/nickalti/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/nickalti/"][j][i]["entry_date"] = category_dict["/kategori/nickalti/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/nickalti/"][j][i]["number_of_likes"] != category_dict["/kategori/nickalti/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/nickalti/"][j][i]["number_of_likes"] = category_dict["/kategori/nickalti/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/nickalti/"][j][i]["number_of_dislikes"] != category_dict["/kategori/nickalti/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/nickalti/"][j][i]["number_of_dislikes"] = category_dict["/kategori/nickalti/"][j][i]["number_of_dislikes"]

    if ("/kategori/oyunlar/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/oyunlar/"])):

            for i in range(1,len(category_dict_edit["/kategori/oyunlar/"][j])):
                if(category_dict_edit["/kategori/oyunlar/"][j][i]["entry_date"] != category_dict["/kategori/oyunlar/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/oyunlar/"][j][i]["entry_text"] = category_dict["/kategori/oyunlar/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/oyunlar/"][j][i]["entry_date"] = category_dict["/kategori/oyunlar/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/oyunlar/"][j][i]["number_of_likes"] != category_dict["/kategori/oyunlar/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/oyunlar/"][j][i]["number_of_likes"] = category_dict["/kategori/oyunlar/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/oyunlar/"][j][i]["number_of_dislikes"] != category_dict["/kategori/oyunlar/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/oyunlar/"][j][i]["number_of_dislikes"] = category_dict["/kategori/oyunlar/"][j][i]["number_of_dislikes"]

    if ("/kategori/saglik/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/saglik/"])):

            for i in range(1,len(category_dict_edit["/kategori/saglik/"][j])):
                if(category_dict_edit["/kategori/saglik/"][j][i]["entry_date"] != category_dict["/kategori/saglik/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/saglik/"][j][i]["entry_text"] = category_dict["/kategori/saglik/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/saglik/"][j][i]["entry_date"] = category_dict["/kategori/saglik/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/saglik/"][j][i]["number_of_likes"] != category_dict["/kategori/saglik/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/saglik/"][j][i]["number_of_likes"] = category_dict["/kategori/saglik/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/saglik/"][j][i]["number_of_dislikes"] != category_dict["/kategori/saglik/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/saglik/"][j][i]["number_of_dislikes"] = category_dict["/kategori/saglik/"][j][i]["number_of_dislikes"]

    if ("/kategori/sanat/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/sanat/"])):

            for i in range(1,len(category_dict_edit["/kategori/sanat/"][j])):
                if(category_dict_edit["/kategori/sanat/"][j][i]["entry_date"] != category_dict["/kategori/sanat/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/sanat/"][j][i]["entry_text"] = category_dict["/kategori/sanat/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/sanat/"][j][i]["entry_date"] = category_dict["/kategori/sanat/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/sanat/"][j][i]["number_of_likes"] != category_dict["/kategori/sanat/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/sanat/"][j][i]["number_of_likes"] = category_dict["/kategori/sanat/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/sanat/"][j][i]["number_of_dislikes"] != category_dict["/kategori/sanat/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/sanat/"][j][i]["number_of_dislikes"] = category_dict["/kategori/sanat/"][j][i]["number_of_dislikes"]

    if ("/kategori/sinema/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/sinema/"])):

            for i in range(1,len(category_dict_edit["/kategori/sinema/"][j])):
                if(category_dict_edit["/kategori/sinema/"][j][i]["entry_date"] != category_dict["/kategori/sinema/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/sinema/"][j][i]["entry_text"] = category_dict["/kategori/sinema/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/sinema/"][j][i]["entry_date"] = category_dict["/kategori/sinema/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/sinema/"][j][i]["number_of_likes"] != category_dict["/kategori/sinema/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/sinema/"][j][i]["number_of_likes"] = category_dict["/kategori/sinema/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/sinema/"][j][i]["number_of_dislikes"] != category_dict["/kategori/sinema/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/sinema/"][j][i]["number_of_dislikes"] = category_dict["/kategori/sinema/"][j][i]["number_of_dislikes"]

    if ("/kategori/siyaset/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/siyaset/"])):

            for i in range(1,len(category_dict_edit["/kategori/siyaset/"][j])):
                if(category_dict_edit["/kategori/siyaset/"][j][i]["entry_date"] != category_dict["/kategori/siyaset/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/siyaset/"][j][i]["entry_text"] = category_dict["/kategori/siyaset/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/siyaset/"][j][i]["entry_date"] = category_dict["/kategori/siyaset/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/siyaset/"][j][i]["number_of_likes"] != category_dict["/kategori/siyaset/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/siyaset/"][j][i]["number_of_likes"] = category_dict["/kategori/siyaset/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/siyaset/"][j][i]["number_of_dislikes"] != category_dict["/kategori/siyaset/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/siyaset/"][j][i]["number_of_dislikes"] = category_dict["/kategori/siyaset/"][j][i]["number_of_dislikes"]


    if ("/kategori/spor/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/spor/"])):

            for i in range(1,len(category_dict_edit["/kategori/spor/"][j])):
                if(category_dict_edit["/kategori/spor/"][j][i]["entry_date"] != category_dict["/kategori/spor/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/spor/"][j][i]["entry_text"] = category_dict["/kategori/spor/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/spor/"][j][i]["entry_date"] = category_dict["/kategori/spor/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/spor/"][j][i]["number_of_likes"] != category_dict["/kategori/spor/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/spor/"][j][i]["number_of_likes"] = category_dict["/kategori/spor/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/spor/"][j][i]["number_of_dislikes"] != category_dict["/kategori/spor/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/spor/"][j][i]["number_of_dislikes"] = category_dict["/kategori/spor/"][j][i]["number_of_dislikes"]

    if ("/kategori/tarih/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/tarih/"])):

            for i in range(1,len(category_dict_edit["/kategori/tarih/"][j])):
                if(category_dict_edit["/kategori/tarih/"][j][i]["entry_date"] != category_dict["/kategori/tarih/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/tarih/"][j][i]["entry_text"] = category_dict["/kategori/tarih/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/tarih/"][j][i]["entry_date"] = category_dict["/kategori/tarih/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/tarih/"][j][i]["number_of_likes"] != category_dict["/kategori/tarih/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/tarih/"][j][i]["number_of_likes"] = category_dict["/kategori/tarih/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/tarih/"][j][i]["number_of_dislikes"] != category_dict["/kategori/tarih/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/tarih/"][j][i]["number_of_dislikes"] = category_dict["/kategori/tarih/"][j][i]["number_of_dislikes"]

    if ("/kategori/teknoloji/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/teknoloji/"])):

            for i in range(1,len(category_dict_edit["/kategori/teknoloji/"][j])):
                if(category_dict_edit["/kategori/teknoloji/"][j][i]["entry_date"] != category_dict["/kategori/teknoloji/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/teknoloji/"][j][i]["entry_text"] = category_dict["/kategori/teknoloji/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/teknoloji/"][j][i]["entry_date"] = category_dict["/kategori/teknoloji/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/teknoloji/"][j][i]["number_of_likes"] != category_dict["/kategori/teknoloji/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/teknoloji/"][j][i]["number_of_likes"] = category_dict["/kategori/teknoloji/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/teknoloji/"][j][i]["number_of_dislikes"] != category_dict["/kategori/teknoloji/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/teknoloji/"][j][i]["number_of_dislikes"] = category_dict["/kategori/teknoloji/"][j][i]["number_of_dislikes"]

    if ("/kategori/televizyon/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/televizyon/"])):

            for i in range(1,len(category_dict_edit["/kategori/televizyon/"][j])):
                if(category_dict_edit["/kategori/televizyon/"][j][i]["entry_date"] != category_dict["/kategori/televizyon/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/televizyon/"][j][i]["entry_text"] = category_dict["/kategori/televizyon/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/televizyon/"][j][i]["entry_date"] = category_dict["/kategori/televizyon/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/televizyon/"][j][i]["number_of_likes"] != category_dict["/kategori/televizyon/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/televizyon/"][j][i]["number_of_likes"] = category_dict["/kategori/televizyon/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/televizyon/"][j][i]["number_of_dislikes"] != category_dict["/kategori/televizyon/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/televizyon/"][j][i]["number_of_dislikes"] = category_dict["/kategori/televizyon/"][j][i]["number_of_dislikes"]

    if ("/kategori/yasam/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/yasam/"])):

            for i in range(1,len(category_dict_edit["/kategori/yasam/"][j])):
                if(category_dict_edit["/kategori/yasam/"][j][i]["entry_date"] != category_dict["/kategori/yasam/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/yasam/"][j][i]["entry_text"] = category_dict["/kategori/yasam/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/yasam/"][j][i]["entry_date"] = category_dict["/kategori/yasam/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/yasam/"][j][i]["number_of_likes"] != category_dict["/kategori/yasam/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/yasam/"][j][i]["number_of_likes"] = category_dict["/kategori/yasam/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/yasam/"][j][i]["number_of_dislikes"] != category_dict["/kategori/yasam/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/yasam/"][j][i]["number_of_dislikes"] = category_dict["/kategori/yasam/"][j][i]["number_of_dislikes"]

    if ("/kategori/yeme-icme/" in category_dict_edit):
        for j in range(0,len(category_dict_edit["/kategori/yeme-icme/"])):

            for i in range(1,len(category_dict_edit["/kategori/yeme-icme/"][j])):
                if(category_dict_edit["/kategori/yeme-icme/"][j][i]["entry_date"] != category_dict["/kategori/yeme-icme/"][j][i]["entry_date"]):
                    category_dict_edit["/kategori/yeme-icme/"][j][i]["entry_text"] = category_dict["/kategori/yeme-icme/"][j][i]["entry_text"]
                    category_dict_edit["/kategori/yeme-icme/"][j][i]["entry_date"] = category_dict["/kategori/yeme-icme/"][j][i]["entry_date"]

                if(category_dict_edit["/kategori/yeme-icme/"][j][i]["number_of_likes"] != category_dict["/kategori/yeme-icme/"][j][i]["number_of_likes"]):
                    category_dict_edit["/kategori/yeme-icme/"][j][i]["number_of_likes"] = category_dict["/kategori/yeme-icme/"][j][i]["number_of_likes"]

                if(category_dict_edit["/kategori/yeme-icme/"][j][i]["number_of_dislikes"] != category_dict["/kategori/yeme-icme/"][j][i]["number_of_dislikes"]):
                    category_dict_edit["/kategori/yeme-icme/"][j][i]["number_of_dislikes"] = category_dict["/kategori/yeme-icme/"][j][i]["number_of_dislikes"]

    with open('enGuncelVeri.json', 'w', encoding='utf-8') as outfile:
        json.dump(category_dict_edit, outfile, sort_keys=True, indent=4, ensure_ascii=False)

comparer()
#set_Limits()