package eu.tsalliance.tsmedia.service;

import eu.tsalliance.tsmedia.models.member.Member;
import eu.tsalliance.tsmedia.models.member.Role;

import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;
import org.springframework.web.client.RestTemplate;

import java.util.*;

@Service
public class MemberService implements UserDetailsService {

    public Member findProfileById(String id) {
        RestTemplate template = new RestTemplate();

        Member.MemberResponseEntity result = template.getForObject("https://api.tsalliance.eu/members/" + id, Member.MemberResponseEntity.class);

        Member member = new Member();
        member.setId(result.getData().getOrDefault("uuid", null).toString());
        member.setName(result.getData().getOrDefault("name", null).toString());
        member.setAvatar(result.getData().getOrDefault("avatar", null).toString());
        return member;
    }

    @Override
    public UserDetails loadUserByUsername(String token) throws UsernameNotFoundException {
        try {

            RestTemplate template = new RestTemplate();
            template.getInterceptors().add((httpRequest, body, clientHttpRequestExecution) -> {
                httpRequest.getHeaders().set("Authorization", "Bearer " + token);
                return clientHttpRequestExecution.execute(httpRequest, body);
            });

            Member.MemberResponseEntity result = template.getForObject("https://api.tsalliance.eu/members/@me", Member.MemberResponseEntity.class);

            Member member = new Member();
            member.setId(result.getData().getOrDefault("uuid", null).toString());
            member.setName(result.getData().getOrDefault("name", null).toString());
            member.setAvatar(result.getData().getOrDefault("avatar", null).toString());

            Role role = new Role();
            Map<String, Object> roleData = (Map<String, Object>) result.getData().get("role");

            role.setId(roleData.getOrDefault("uuid", null).toString());

            ArrayList<String> permissions = (ArrayList<String>) roleData.getOrDefault("permissions", new ArrayList<>());
            ArrayList<GrantedAuthority> permissionAuthorities = new ArrayList<>();

            for(String permission : permissions) {
                permissionAuthorities.add(new SimpleGrantedAuthority(permission));
            }

            role.setPermissions(new HashSet<>(permissionAuthorities));
            role.setName(roleData.getOrDefault("name", null).toString());

            member.setRole(role);

            return member;
        } catch (Exception ex) {
            return Member.anonymous();
        }
    }
}
