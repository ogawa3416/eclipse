package com.example.demo;

import java.util.ArrayList;
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
		List<User> users = new ArrayList<User>();
		users.add(new User("山田", "mail1@gmail.com", "20"));
		users.add(new User("佐藤", "mail2@gmail.com", "21"));
		MyUtil.SaveCSV(users);
	}

	@Test
	public void mytest7() {
		List<User> users = MyUtil.ReadCSV("test.CSV");
		for (int i = 0; i < users.size(); i++) {
			System.out.println(
					"name: " + users.get(i).name + ", mail: " + users.get(i).mail + ", age" + users.get(i).age);
		}
	}

}
