package com.example.demo;

import java.util.List;

import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.context.junit4.SpringRunner;

@RunWith(SpringRunner.class)
@SpringBootTest
public class SampleFormApplicationTests {

	@Test
	public void mytest() {

		MyUtil.saveFile();
	}

	@Test
	public void mytest2() {
		MyUtil.saveFile("test");
	}

	@Test
	public void mytest3() {
		MyUtil.saveFile("ok", "testfile.txt");
	}

	@Test
	public void mytest4() {
		MyUtil.loadFile("test.txt");
	}

	@Test
	public void mytest5() {
		List<String> text = MyUtil.loadFile("test.txt");
		System.out.println(text);
	}

	@Test
	public void mytest6() {
		String[] nameList = { "田中", "鈴木", "木村" };
		String[] mailList = { "tanaka@gmail.com", "suzuki@gmail.com", "kimura@gmail.com" };
		int[] ageList = { 21, 35, 28 };
		User.SaveCSV(nameList, mailList, ageList);
	}

	@Test
	public void mytest7() {
		User.ReadCSV();
	}

}
