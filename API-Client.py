import requests
import pprint
import datetime
import mysql.connector

url = "https://yahoo-finance-low-latency.p.rapidapi.com/v6/finance/quote"

querystring = {"symbols":"ALTR.LS,PSI20.LS,BCP.LS,COR.LS,CTT.LS,EDP.LS,EDPR.LS,EGL.LS,GALP.LS,IBS.LS,JMT.LS,NBA.LS,NOS.LS,NVG.LS,PHR.LS,RAM.LS,RENE.LS,SEM.LS,YSO.LS"}

headers = {
    'x-rapidapi-key': "placeholder",
    'x-rapidapi-host': "yahoo-finance-low-latency.p.rapidapi.com"
    }

mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database='tp-bd-a66453'
)

response = requests.request("GET", url, headers=headers, params=querystring)
jsonResponse = response.json()
results=jsonResponse['quoteResponse']['result']
for r in results:
    print(50*'*')
    print(r['shortName'])
    #print(r['symbol'])  
    #pprint.pprint(r)
    valor= r['regularMarketPrice']
    if r['regularMarketChange']<0:
        sinal = -1 
    
    else:
        sinal= 1

    dataHora= datetime.datetime.utcnow().strftime('%Y-%m-%d %H:%M:%S')
    transacionadoUltimaHora= r['regularMarketVolume']
    Ativo_idAtivo= r['symbol']
    Nome = r['shortName']
    #valor = r['bookValue']
    sql=f'''INSERT INTO `tp-bd-a66453`.`cotacao`
    (`valor`,`sinal`,`dataHora`,`transacionadoUltimaHora`,`Ativo_idAtivo`)
    VALUES
    ({valor },{sinal },"{dataHora }",{transacionadoUltimaHora }, '{Ativo_idAtivo }');'''
    print(sql)
    with mydb.cursor() as cursor:
        cursor.execute(sql)
        mydb.commit()
    

mydb.close()
