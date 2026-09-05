package server;

import java.io.IOException;
import stream.Server;

public class NinjaSchool {

    public static void main(String[] args) throws IOException {
        Runtime.getRuntime().addShutdownHook(new Thread(new Runnable() {
            public void run() {
                try {
                    Server.close(10L);
                } catch (InterruptedException e) {
                    e.printStackTrace();
                }
            }
        }));
        Server.start(true);
    }
}
