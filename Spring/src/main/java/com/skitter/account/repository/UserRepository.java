package com.skitter.account.repository;

import com.skitter.account.model.User;
import org.springframework.data.jpa.repository.JpaRepository;

public interface UserRepository extends JpaRepository<User, Long> {
    User findByUsername(String username);
    User findByDisplayName(String displayName);
    void delete(User user);
}
