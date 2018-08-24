package com.example.demo;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;
import java.util.StringTokenizer;

public class User {
	private String name;
	private String mail;
	private String age;
	private List<String> nameList = new ArrayList<>();
	private List<String> mailList = new ArrayList<>();
	private List<String> ageList = new ArrayList<>();

	public User(String name, String mail, String age) {
		this.name = name;
		this.mail = mail;
		this.age = age;
		nameList.add(name);
		mailList.add(mail);
		ageList.add(age);
	}

	// CSVに追記
	public static void SaveCSV(String[] nameList, String[] mailList, int[] ageList) {

		File file = new File("C:/Users/Kohei Ogawa/Documents/test.CSV");

		try {
			PrintWriter pw = new PrintWriter(
					new BufferedWriter(new OutputStreamWriter(new FileOutputStream(file, true), "Shift-JIS")));

			pw.print("名前");
			pw.print(",");
			pw.print("メールアドレス");
			pw.print(",");
			pw.print("年齢");
			pw.println();

			for (int i = 0; i < nameList.length; i++) {
				pw.print(nameList[i]);
				pw.print(",");
				pw.print(mailList[i]);
				pw.print(",");
				pw.print(ageList[i]);
				pw.println();
			}

			pw.close();

			System.out.println("ファイル出力完了");

		} catch (IOException e) {
			e.printStackTrace();
		}

	}

	// CSVの読み込み
	public static void ReadCSV() {
		try {
			File csv = new File("C:/Users/Kohei Ogawa/Documents/test.CSV");
			BufferedReader br = new BufferedReader(new FileReader(csv));

			String line = "";
			while ((line = br.readLine()) != null) {

				StringTokenizer st = new StringTokenizer(line, ",");

				while (st.hasMoreTokens()) {
					System.out.print(st.nextToken() + "\t");
				}
				System.out.println();
			}
			br.close();

		} catch (FileNotFoundException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}