#include <stdio.h>
#include <stdlib.h>
#include "libxl.h"
#include <string.h>
#include <stdint.h>
#include <curl/curl.h>

typedef struct Data {
  double weight;
  double length;
  double height;
  double width ;
  char nameRecipient[100];
  char emailRecipient[30] ;
  char addressRecipient[100];
  uint8_t delay ;
} Data ;

void readData (char **argv);
uint8_t saveData (Data *datas, char **argv) ;
void printReturn (char *str, size_t size, size_t nmemb, void *stream) ;


int main (int argc, char **argv) {
    if (argc < 4)
        return 1 ;

    readData(argv);
    return 0;
}


void printReturn (char *str, size_t size, size_t nmemb, void *stream) {
    printf("%s\n", str) ;
}

/*
Function : readData
--------------------------------------------------------------------------------
Reads the data from an XLS file
Calls
    (saveData) to insert the data in the database

--------------------------------------------------------------------------------
char **argv : array containing the name of the excel file and the number of packages
--------------------------------------------------------------------------------

*/
void readData (char **argv) {
    Data *datas = malloc(sizeof(Data) * atoi(argv[3]));
    BookHandle book = xlCreateBook();
    char bookLocation[70] = "/var/www/html/uploads/excel/";
    strcat(bookLocation, argv[1]);
    if (book) {
        if (xlBookLoad(book, bookLocation)) {
            SheetHandle sheet = xlBookGetSheet(book, 0);
            if (sheet) {
                for (size_t lin = 1; lin <= atoi(argv[3]); lin++) {
                    datas[lin-1].weight = xlSheetReadNum(sheet, lin, 0, 0);
                    datas[lin-1].length = xlSheetReadNum(sheet, lin, 1, 0);
                    datas[lin-1].height = xlSheetReadNum(sheet, lin, 2, 0);
                    datas[lin-1].width = xlSheetReadNum(sheet, lin, 3, 0);
                    strcpy(datas[lin-1].emailRecipient, xlSheetReadStr(sheet, lin, 4, 0));
                    strcpy(datas[lin-1].addressRecipient, xlSheetReadStr(sheet, lin, 5, 0));
                    datas[lin-1].delay = xlSheetReadNum(sheet, lin, 6, 0);
                    strcpy(datas[lin-1].nameRecipient, xlSheetReadStr(sheet, lin, 7, 0));
                }
            }
        }

        xlBookRelease(book);
    }

    saveData(datas, argv) ;
    free(datas);
}


/*
Function : saveData
--------------------------------------------------------------------------------
Connects to the mysql database to insert the data

--------------------------------------------------------------------------------
Datas *data : structure containing the package data
char **argv : array containing the name of the excel file and the number of packages
--------------------------------------------------------------------------------
Return values
    1 if an error occured
    0 otherwise
*/
uint8_t saveData (Data *datas, char **argv) {
    CURL *curl;
    CURLcode res;
    struct curl_slist *headerlist = NULL;

    char insert[300] = "";
    char json[400] = "";
    for (uint8_t i = 0; i < atoi(argv[3]); i++) {
        curl_global_init(CURL_GLOBAL_ALL);

        strcpy(insert, "") ;
        sprintf(insert, "INSERT INTO PACKAGE (weight, volume, address, email, delay, client, excelPath, nameRecipient) VALUES (%lf, %lf, '%s', '%s', %d, %d, '/%s', '%s')",
            datas[i].weight,
            datas[i].length * datas[i].height * datas[i].width,
            datas[i].addressRecipient,
            datas[i].emailRecipient,
            datas[i].delay,
            atoi(argv[2]),
            argv[1],
            datas[i].nameRecipient
        );
printf("%s\n", insert);
        strcat(strcat(strcpy(json,"{ \"insert\": \""), insert), "\"}");

        curl = curl_easy_init();
        if(curl) {
          /* initialize custom header list (stating that Expect: 100-continue is not
             wanted */
          headerlist = curl_slist_append(headerlist, "Expect:");
          headerlist = curl_slist_append(headerlist, "Content-Type: application/json");
          curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headerlist);

          curl_easy_setopt(curl, CURLOPT_POSTFIELDS, json);
          curl_easy_setopt(curl, CURLOPT_POSTFIELDSIZE, -1L);

          /* what URL that receives this POST */
          curl_easy_setopt(curl, CURLOPT_URL, "http://localhost/api/package/");
          //curl_easy_setopt(curl, CURLOPT_MIMEPOST, form);


          /* Store the result of the query */
          curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, printReturn);

          /* Perform the request, res will get the return code */
          res = curl_easy_perform(curl);
          /* Check for errors */
          if(res != CURLE_OK && res != CURLE_WRITE_ERROR)
            return res ;



        }

    }
    /* always cleanup */
    curl_easy_cleanup(curl);
    /* free slist */
    curl_slist_free_all(headerlist);
    return 0;
}
