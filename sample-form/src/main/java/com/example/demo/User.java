package com.example.demo;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;

public class User {
	public static void SaveCsv(String[] nameList, String[] mailList, int[] ageList) {

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

}