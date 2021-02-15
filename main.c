#include <stdio.h>
#include <stdlib.h>
#include "libxl.h"
#include <string.h>
#include <mysql.h>
#include <stdint.h>

uint16_t port = 3306 ;
char *host = "esilxl0nthgloe1y.chr7pe7iynqr.eu-west-1.rds.amazonaws.com" ;
char *usrn = "uxqgwq6h6ua6ytj4" ;
char *pwd = "t68v3kbb45z29tj5" ;
char *dbname = "xaozppxjop6wx0s1" ;


typedef struct Data {
  double weight ;
  double length;
  double height;
  double width ;
  char emailRecipient[30] ;
  char addressRecipient[100];
  uint8_t delay ;
} Data ;

void readData (char **argv);
uint8_t saveData (Data *datas, char **argv) ;
int16_t getPkgNumber (void) ;


int main (int argc, char **argv) {
    if (argc < 4)
        return 1 ;

    readData(argv);
    return 0;
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
    if (book) {
        if (xlBookLoad(book, argv[1])) {
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
    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");
    char insert[300] = "";

    if (mysql_real_connect(&mysql, host, usrn, pwd, dbname, port, NULL, 0) == NULL) {
        printf("Unable to connect to mysql\n");
        mysql_close(&mysql);
        return 1 ;
    }
    for (uint8_t i = 0; i < atoi(argv[3]); i++) {
        strcpy(insert, "") ;
        sprintf(insert, "INSERT INTO PACKAGE (weight, volume, address, email, delay, client) VALUES (%lf, %lf, '%s', '%s', %d, %d);",
            datas[i].weight,
            datas[i].length * datas[i].height * datas[i].width,
            datas[i].emailRecipient,
            datas[i].addressRecipient,
            datas[i].delay,
            atoi(argv[2])
        );

        if(mysql_query(&mysql, insert)){      // mysql_query returns 0 if sucess
          printf("Unable to insert data in DB\n" );
          exit(1);
        }
        printf("%d\n", getPkgNumber()) ;
    }
    mysql_close(&mysql);

    return 0 ;
}

/*
Function : getPkgNumber
--------------------------------------------------------------------------------
Selects the maximal id in the package table

--------------------------------------------------------------------------------
Return values
    the id of the package
    -1 if couldn't connect to the database
*/
int16_t getPkgNumber (void) {
    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");
    char *selectMax = "SELECT MAX(id) FROM PACKAGE" ;
    MYSQL_ROW row ;
    MYSQL_RES *results ;
    uint16_t nb = 0 ;

    if(mysql_real_connect(&mysql, host, usrn, pwd, dbname, port, NULL, 0) == NULL){
        printf("Unable to connect to mysql\n");
        mysql_close(&mysql);
        return -1 ;
    } else {
        mysql_query(&mysql, selectMax);
        results = mysql_use_result(&mysql) ;
        if (results == NULL)
            return 0 ;

        if( row = mysql_fetch_row(results), (row[0]) )
          nb = atoi(row[0]) ;
        else {
          printf("Error select max id\n");
          exit(1);
        }
        mysql_close(&mysql);
    }
    return nb ;
}
