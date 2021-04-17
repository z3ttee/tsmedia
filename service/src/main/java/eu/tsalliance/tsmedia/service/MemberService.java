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

    /**
     * Get info about the member associated to access token
     * @return Member
     */
    public Member findMemberByToken(String token) {

        return new Member();

        /**/
    }

    public Member findMemberById(String id) {
        return new Member();
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
