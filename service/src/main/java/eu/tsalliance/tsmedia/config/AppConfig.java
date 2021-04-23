package eu.tsalliance.tsmedia.config;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Configuration;
import org.springframework.web.servlet.config.annotation.ResourceHandlerRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;

import java.nio.file.Path;

@Configuration
public class AppConfig implements WebMvcConfigurer {

    /*@Autowired
    private FileStorageConfig storageConfig;

    @Override
    public void addResourceHandlers(ResourceHandlerRegistry registry) {
        Path externalStaticLocations = Path.of(this.storageConfig.getUploadDir());

        System.out.println("file:" + externalStaticLocations.toAbsolutePath());

        registry
                .addResourceHandler("/test/**")
                .addResourceLocations("file:" + externalStaticLocations.toAbsolutePath());
    }*/
}
