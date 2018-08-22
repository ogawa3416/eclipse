package com.example.demo;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class DemoController {
	@RequestMapping("/")
	public String index() {
		return "index";
	}

	@RequestMapping("/confirm")
	public String confirm(@ModelAttribute("msg1") String str, @ModelAttribute("msg2") String path) {
		System.out.println("msg1:" + str);
		System.out.println("msg2:" + path);
		MyUtil.saveFile(str, path);
		return "index";
	}
}