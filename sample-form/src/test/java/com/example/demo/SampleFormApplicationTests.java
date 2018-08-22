package com.example.demo;

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

}
