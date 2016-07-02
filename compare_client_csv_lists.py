#!/usr/bin/env python

import csv

vector_csv = "Sib_clients_Vector.csv"
vast_action_csv = "Sib_VastAction_all.csv"
missing_client_csv = "missing_clients.csv"


def main():
    vector_list = get_clients_from_csv(vector_csv, 1, 2)
    vast_action_list = get_clients_from_csv(vast_action_csv, 7, 8)
    write_missing_clients(vast_action_list, vector_list)


def get_clients_from_csv(filename, fn_col, ln_col):
    clients = {}
    rowcount = 0
    duplicates = 0

    with open(filename, 'rb') as csvfile:
        reader = csv.reader(csvfile, delimiter=',', quotechar='"',
            quoting=csv.QUOTE_MINIMAL)
        for row in reader:
            rowcount += 1

            if (rowcount == 1):
                print filename + ' cols: ' + row[fn_col] + ", " + row[ln_col]
                continue

            this_name = row[fn_col].lower() + '_' + row[ln_col].lower()
            this_name.strip()

            if this_name in clients:
                # print "Duplicate name: " + row[fn_col] + ' ' + row[ln_col]
                duplicates += 1
            else:
                clients[this_name] = row

    print "Read " + str(rowcount) + " rows, with " + str(duplicates) + " duplicates"
    return clients


def write_missing_clients(list1, list2):
    rowcount = 0
    with open(missing_client_csv, 'wb') as csvfile:
        writer = csv.writer(csvfile, delimiter=',', quotechar='"',
            quoting=csv.QUOTE_MINIMAL)

        for key, value in list1.items():
            if key in list2:
                continue
            else:
                writer.writerow(value)
                rowcount += 1

    print "Wrote " + str(rowcount) + " missing clients to " + missing_client_csv

if __name__ == '__main__':
    main()
