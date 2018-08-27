package com.example.demo;

import java.util.ArrayList;
import java.util.List;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class DemoController {
	@RequestMapping("/")
	public String index() {
		return "index";
	}

	@RequestMapping("/read")
	public String read() {
		return "read";
	}

	@RequestMapping("/confirm")
	public String confirm(@ModelAttribute("msg1") String name, @ModelAttribute("msg2") String mail,
			@ModelAttribute("msg3") String age) {
		System.out.println("msg1:" + name);
		System.out.println("msg2:" + mail);
		System.out.println("msg3:" + age);
		// MyUtil.saveFile(str, path);

		List<User> users = new ArrayList<User>();
		users.add(new User(name, mail, age));
		MyUtil.SaveCSV(users);
		return "index";
	}

	@RequestMapping("/confirm1")
	public String confirm(@ModelAttribute("msg1") String path) {
		List<User> users = MyUtil.ReadCSV(path);

		return "read";
	}
}