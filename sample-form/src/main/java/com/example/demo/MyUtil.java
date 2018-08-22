package com.example.demo;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;

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

	public static void loadFile(String path) {
		System.out.println("ファイルをロードします");
		System.out.println("------------------------------");

		String path1 = "C:/Users/Kohei Ogawa/Documents/" + path;

		try {
			File file = new File(path1);

			if (file.exists()) {
				FileReader filereader = new FileReader(file);

				int data;
				while ((data = filereader.read()) != -1) {
					System.out.print((char) data);
				}
				filereader.close();
			} else {
				System.out.println("ファイルは存在しません");
			}
		} catch (IOException e) {
			e.printStackTrace();
		}

		System.out.println("------------------------------");
	}
}