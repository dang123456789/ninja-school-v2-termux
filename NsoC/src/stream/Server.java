package stream;

import assembly.ClanManager;
import assembly.Map;
import assembly.Player;
import io.SQLManager;
import server.*;
import template.MapTemplate;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.net.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JFrame;
import thiendiabang.ThienDiaBangManager;

public class Server extends Thread {

    private static Server instance = null;
    protected static ServerSocket listenSocket = null;
    public static boolean start = false;
    public static Manager manager;
    public static Menu menu;
    public static Controller serverMessageHandler;
    public static Map[] maps;
    public static Object LOCK_MYSQL = new Object();
    public static boolean running = true;
    public static RunTimeServer runTime = new RunTimeServer();
    public static Shinwa runShinwa = new Shinwa();
    public static ByteArrayOutputStream[] cache = new ByteArrayOutputStream[5];

    public static int baseId = 0;

    public Server() {
        this.listenSocket = null;
    }

    public static Server gI() {
        if (Server.instance == null) {
            Server.instance = new Server();
            Server.instance.init();
            Rank.init();
            Server.runTime.start();
            Server.runShinwa.start();
        }
        return Server.instance;
    }

    private synchronized void init() {
        manager = new Manager();
        menu = new Menu();
        serverMessageHandler = new Controller();
        cache[1] = GameSrc.loadFile("res/cache/map");
        cache[4] = GameSrc.loadFile("res/cache/map_2_211");
        cache[2] = GameSrc.loadFile("res/cache/skill");
        cache[3] = GameSrc.loadFile("res/cache/item");
        this.maps = new Map[MapTemplate.arrTemplate.length];
        short i;
        for (i = 0; i < this.maps.length; ++i) {
            this.maps[i] = new Map(i, null, null, null, null, null, null, null);
            this.maps[i].start();
        }

    }

    public static void start(boolean running) throws IOException {
        try {
            gI().start();
            initializeGUI();
            listenSocket = new ServerSocket(Server.manager.post);
            System.out.println("Listen port: " + Server.manager.post);
            start = running;
            while (start) {
                try {
                    Socket clientSocket = listenSocket.accept();
                    Session conn = new Session(clientSocket, serverMessageHandler);
                    Client.gI().put(conn);
                    conn.run();
                } catch (IOException e) {
                }
            }
            System.out.println("Close server!");
        } catch (IOException e) {
        }
    }

    public static void close(long delayKick) throws InterruptedException {
        if (Server.start) {
            Server.start = false;
            try {
                Server.listenSocket.close();
            } catch (IOException ex) {
                ex.printStackTrace();
            }
            synchronized (Server.LOCK_MYSQL) {
                Server.LOCK_MYSQL.wait(delayKick);
            }
            synchronized (Server.LOCK_MYSQL) {
                ClanManager.close();
                ShinwaManager.close();
                ThienDiaBangManager.close();
                Client.gI().Clear();
            }

            Client.gI().close();
            Server.manager.close();
            Server.manager = null;
            Server.maps = null;
            Server.cache = null;
            Server.serverMessageHandler = null;
            Server.runTime = null;
            Server.runShinwa = null;
            Server.LOCK_MYSQL = null;
            SQLManager.close();
            System.out.println("BaoTriHoanTat");
            System.gc();
        }
    }
    
    public static void initializeGUI() {
    }
}

