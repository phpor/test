#include <iostream>
#include <fstream>

using namespace std;

const int BUFSIZE = 1024 * 1024;

int dump(ifstream *in) {
	char* ch = new char[8];
	int i = 0, j = 0, pos = 0, cnt = 0;
	char c;
	while (i < 8) {
		*(ch+i) = ('\x01' << (7 - i));
		i++;
	}
	while(!in->eof()) {
		*in >> c;
		j = 0;
		while (j < 8) {
			if ((ch[j] & c) == ch[j]) {
				cnt++;
				cout << pos * 8 + j << endl;
			}
			j++;
		}
		pos++;
	}
	return cnt;
}
int main(int ac, char** av) {
	string filename = "activeuser.bin";
	char* pchar = new char[BUFSIZE];
	char* ch = new char[8];

	int len, pos, cnt = 0, i, j;
	char c;

	if (ac > 1) {
		filename = string(av[1]);
	}
	ifstream in("activeuser.bin");
	if (!in) {
		cout << "open file fail" <<endl;
	}
	cout << "count:" << dump(&in) <<endl;
return 0;
	pos = 0;

	i = 0;
	while (i < 8) {
		*(ch+i) = ('\x01' << (7 - i));
		//cout << (ch[i]) << endl;
		//printf("%d\n", (ch[i]));
		i++;
	}
	//cout << "ret:" << in.read(pchar, BUFSIZE) << endl;
	//cout << "read:" << pchar <<endl;
	while(!in.eof()) {
		in.read(pchar, BUFSIZE);
		if (in.bad()) break;
		//cout << pos << endl;
		len = in.gcount();
		i = 0;
		while (i < len) {
			c = pchar[i];
			j = 0;
			while (j < 8) {
				//printf("%x::%x\n", ch[j], c);
				if ((ch[j] & c) == ch[j]) {
					cnt++;
					cout << pos * 8 + j << endl;
				}
				//cout << pos << ":" << j << endl;
				j++;
			}
			pos++;
			i++;
			//if (pos > 10 ) break;
		}
	}
	cout << "count: " << cnt <<endl;

	in.close();
	delete pchar;
	delete ch;

	return 0;
}
