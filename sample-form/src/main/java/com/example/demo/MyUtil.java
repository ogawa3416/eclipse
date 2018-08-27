package com.example.demo;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;

import org.springframework.stereotype.Controller;

@Controller
public class MyUtil {
	public static void saveFile() {
		saveFile("test");
	}

	public static void saveFile(String str) {
		saveFile(str, "java.txt");
	}

	public static void saveFile(String str, String path) {
		System.out.println("ここで文字列" + str + "を" + path + "に保存します");

		String path1 = "C:/Users/Kohei Ogawa/Documents/" + path;

		try {
			FileWriter file = new FileWriter(path1, true);
			PrintWriter pw = new PrintWriter(new BufferedWriter(file));
			pw.println(str);
			pw.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

	// public static String loadFile1(String path) {
	// System.out.println("ファイルをロードします");

	// String path1 = "C:/Users/Kohei Ogawa/Documents/" + path;

	// return path1;
	// }

	public static List<String> loadFile(String path) {
		System.out.println("ファイルをロードします");

		List<String> list = new ArrayList<>();

		String path1 = "C:/Users/Kohei Ogawa/Documents/" + path;
		try {
			File file = new File(path1);

			if (!file.exists()) {
				System.out.print("ファイルが存在しません");
			}

			FileReader fileReader = new FileReader(file);
			BufferedReader bufferedReader = new BufferedReader(fileReader);
			String data;
			while ((data = bufferedReader.readLine()) != null) {
				list.add(data);
			}

			fileReader.close();

		} catch (IOException e) {
			e.printStackTrace();
		}

		return list;
	}

	public static List<User> ReadCSV(String path) {
		String path1 = "C:/Users/Kohei Ogawa/Documents/" + path;

		List<User> list = new ArrayList<User>();
		try {
			File csv = new File(path1);
			BufferedReader br = new BufferedReader(new FileReader(csv));
			String data;

			while ((data = br.readLine()) != null) {
				String[] param = data.split(",", 0);
				User user = new User(param[0], param[1], param[2]);
				list.add(user);
			}
			br.close();

		} catch (FileNotFoundException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return list;
	}

	// CSVに追記
	public static void SaveCSV(List<User> users) {

		File file = new File("C:/Users/Kohei Ogawa/Documents/test.CSV");

		try {
			PrintWriter pw = new PrintWriter(
					new BufferedWriter(new OutputStreamWriter(new FileOutputStream(file, true), "Shift-JIS")));

			for (int i = 0; i < users.size(); i++) {
				pw.print(users.get(i).name);
				pw.print(",");
				pw.print(users.get(i).mail);
				pw.print(",");
				pw.print(users.get(i).age);
				pw.println();
			}

			pw.close();

			System.out.println("ファイル出力完了");

		} catch (IOException e) {
			e.printStackTrace();
		}

	}

}