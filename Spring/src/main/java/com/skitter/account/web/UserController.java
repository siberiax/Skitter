package com.skitter.account.web;

import javax.naming.*;
import javax.naming.directory.*;
import java.util.Hashtable;
import com.skitter.account.model.User;
import com.skitter.account.service.SecurityService;
import com.skitter.account.service.SecurityServiceImpl;
import com.skitter.account.service.UserService;
import com.skitter.account.validator.UserValidator;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;

@Controller
public class UserController {
    @Autowired
    private UserService userService;

    @Autowired
    private SecurityService securityService;

    @Autowired
    private UserValidator userValidator;

    @RequestMapping(value = "/registration", method = RequestMethod.GET)
    public String registration(Model model) {
        model.addAttribute("userForm", new User());

        return "registration";
    }

    @RequestMapping(value = "/registration", method = RequestMethod.POST)
    public String registration(@ModelAttribute("userForm") User userForm, BindingResult bindingResult, Model model) {
        userValidator.validate(userForm, bindingResult);

        if (bindingResult.hasErrors()) {
            return "registration";
        }

        Hashtable env = new Hashtable();
        env.put(Context.INITIAL_CONTEXT_FACTORY,"com.sun.jndi.ldap.LdapCtxFactory");
        env.put(Context.PROVIDER_URL, "ldaps://ldap.rit.edu");
        env.put(Context.SECURITY_AUTHENTICATION,"simple");
        env.put(Context.SECURITY_PRINCIPAL,"uid=" + userForm.getUsername() + ",ou=people,dc=rit,dc=edu"); // specify the username
        env.put(Context.SECURITY_CREDENTIALS, userForm.getPassword());           // specify the password

        try{
            DirContext ctx = new InitialDirContext(env);
            if(ctx != null){
                userService.save(userForm);
                securityService.autologin(userForm.getUsername(), userForm.getPasswordConfirm());

                return "redirect:/login";
            }
        }
        catch(Exception ex){

        }
        return "failed";

        //userService.save(userForm);
        //securityService.autologin(userForm.getUsername(), userForm.getPasswordConfirm());

    }

    @RequestMapping(value = "/login", method = RequestMethod.GET)
    public String login(Model model, String error, String logout) {
        if (error != null)
            model.addAttribute("error", "Your username and password is invalid.");

        if (logout != null)
            model.addAttribute("message", "You have been logged out successfully.");

        return "login";
    }

    @RequestMapping(value = {"/delete"}, method = RequestMethod.GET)
    public String deleteUser(@RequestParam(value="username") String username){
        User user = userService.findByDisplayName(username);
        if(user != null){
            userService.delete(user);
            return "deleted";
        }
        return "failed";
    }

    @RequestMapping(value = {"/", "/welcome"}, method = RequestMethod.GET)
    public String welcome(Model model) {
        return "welcome";
    }
}
