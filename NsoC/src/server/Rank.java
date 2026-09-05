package server;

import assembly.ClanManager;
import io.SQLManager;
import io.Util;
import stream.Server;
import template.ItemTemplate;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Timer;

public class Rank {

    public static ArrayList<Entry>[] bangXH = new ArrayList[8];
    public static Timer t = new Timer(true);
    public static ArrayList<Entry2> bxhCaoThu = new ArrayList<>();

    public static void init() {
        Rank.updateCaoThu();
        for (int i = 0; i < Rank.bangXH.length; ++i) {
            Rank.bangXH[i] = new ArrayList<>();
        }
        System.out.println("load BXH");
        for (int i = 0; i < Rank.bangXH.length; ++i) {
            initBXH(i);
        }
    }

    public static void initBXH(int type) {
        Rank.bangXH[type].clear();
        ArrayList<Entry> bxh = Rank.bangXH[type];
        switch (type) {
            case 0: {
                ResultSet red = null;
                try {
                    int i = 1;
                    red = SQLManager.stat.executeQuery("SELECT `name`,`yen`,`level` FROM `ninja` WHERE (`yen` > 0) ORDER BY `yen` DESC LIMIT 10;");
                    int coin;
                    int level;
                    Entry bXHE;
                    if (red != null) {
                        while (red.next()) {
                            String name = red.getString("name");
                            coin = red.getInt("yen");
                            level = red.getInt("level");
                            bXHE = new Entry();
                            bXHE.nXH = new long[2];
                            bXHE.name = name;
                            bXHE.index = i;
                            bXHE.nXH[0] = coin;
                            bXHE.nXH[1] = level;
                            bxh.add(bXHE);
                            i++;
                        }
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                } finally {
                    if (red != null) {
                        try {
                            red.close();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
                break;
            }
            case 1: {
                ResultSet red = null;
                try {
                    int i = 1;
                    red = SQLManager.stat.executeQuery("SELECT `name`,`exp`,`level`,`class` FROM `ninja` WHERE (`exp` > 0) ORDER BY `exp` DESC LIMIT 10;");
                    long exp;
                    int level2;
                    int nClass;
                    Entry bXHE2;
                    while (red.next()) {
                        String name = red.getString("name");
                        exp = red.getLong("exp");
                        level2 = red.getInt("level");
                        nClass = red.getInt("class");
                        bXHE2 = new Entry();
                        bXHE2.nXH = new long[3];
                        bXHE2.name = name;
                        bXHE2.index = i;
                        bXHE2.nXH[0] = exp;
                        bXHE2.nXH[1] = level2;
                        bXHE2.nXH[2] = nClass;
                        bxh.add(bXHE2);
                        i++;
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                } finally {
                    if (red != null) {
                        try {
                            red.close();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
                break;
            }
            case 2: {
                ResultSet red = null;
                try {
                    int i = 1;
                    red = SQLManager.stat.executeQuery("SELECT `name`,`level` FROM `clan` WHERE (`level` > 0) ORDER BY `level` DESC LIMIT 10;");
                    int level3;
                    Entry bXHE3;
                    while (red.next()) {
                        String name = red.getString("name");
                        level3 = red.getInt("level");
                        bXHE3 = new Entry();
                        bXHE3.nXH = new long[1];
                        bXHE3.name = name;
                        bXHE3.index = i;
                        bXHE3.nXH[0] = level3;
                        bxh.add(bXHE3);
                        i++;
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                } finally {
                    if (red != null) {
                        try {
                            red.close();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
                break;
            }
            case 3: {
                ResultSet red = null;
                try {
                    int i = 1;
                    red = SQLManager.stat.executeQuery("SELECT `name`,`bagCaveMax`,`itemIDCaveMax` FROM `ninja` WHERE (`bagCaveMax` > 0) ORDER BY `bagCaveMax` DESC LIMIT 10;");
                    String name;
                    int cave;
                    short id;
                    Entry bXHE;
                    while (red.next()) {
                        name = red.getString("name");
                        cave = red.getInt("bagCaveMax");
                        id = red.getShort("itemIDCaveMax");
                        bXHE = new Entry();
                        bXHE.nXH = new long[2];
                        bXHE.name = name;
                        bXHE.index = i;
                        bXHE.nXH[0] = cave;
                        bXHE.nXH[1] = id;
                        bxh.add(bXHE);
                        ++i;
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                } finally {
                    if (red != null) {
                        try {
                            red.close();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
                break;
            }
            case 4: {
                ResultSet red = null;
                try {
                    int i = 1;
                    String name;
                    red = SQLManager.stat.executeQuery("SELECT `name`,`eventPoint` FROM `ninja` WHERE (`eventPoint` > 0) ORDER BY `eventPoint` DESC LIMIT 10;");
                    while (red.next()) {
                        name = red.getString("name");
                        int eventPoint = red.getInt("eventPoint");
                        Entry bXHE = new Entry();
                        bXHE.nXH = new long[2];
                        bXHE.name = name;
                        bXHE.index = i;
                        bXHE.nXH[0] = eventPoint;
                        bxh.add(bXHE);
                        i++;
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                } finally {
                    if (red != null) {
                        try {
                            red.close();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
                break;
            }
            case 5: {
                ResultSet red = null;
                try {
                    int i = 1;
                    red = SQLManager.stat.executeQuery("SELECT `name`,`vip` FROM `ninja` WHERE (`vip` > 0) ORDER BY `vip` DESC LIMIT 50;");
                    while (red.next()) {
                        final String name = red.getString("name");
                        int coin = red.getInt("vip");
                        Entry bXHE = new Entry();
                        bXHE.nXH = new long[2];
                        bXHE.name = name;
                        bXHE.index = i;
                        bXHE.nXH[0] = coin;
                        bxh.add(bXHE);
                        i++;
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                } finally {
                    if (red != null) {
                        try {
                            red.close();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
                break;
            }
            case 6: {
                ResultSet red = null;
                try {
                    int i = 1;
                    red = SQLManager.stat.executeQuery("SELECT `name`,`diemnap` FROM `ninja` WHERE (`diemnap` > 0) ORDER BY `diemnap` DESC LIMIT 10;");
                    while (red.next()) {
                        final String name = red.getString("name");
                        int coin = red.getInt("diemnap");
                        Entry bXHE = new Entry();
                        bXHE.nXH = new long[2];
                        bXHE.name = name;
                        bXHE.index = i;
                        bXHE.nXH[0] = coin;
                        bxh.add(bXHE);
                        i++;
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                } finally {
                    if (red != null) {
                        try {
                            red.close();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
                break;
            }
            case 7: {
                ResultSet red = null;
                try {
                    int i = 1;
                    red = SQLManager.stat.executeQuery("SELECT `name`,`pointMax` FROM `ninja` WHERE (`pointMax` > 0) ORDER BY `pointMax` DESC LIMIT 10;");
                    while (red.next()) {
                        final String name = red.getString("name");
                        int coin = red.getInt("pointMax");
                        Entry bXHE = new Entry();
                        bXHE.nXH = new long[2];
                        bXHE.name = name;
                        bXHE.index = i;
                        bXHE.nXH[0] = coin;
                        bxh.add(bXHE);
                        i++;
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                } finally {
                    if (red != null) {
                        try {
                            red.close();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
                break;
            }
        }
    }

    public static Entry[] getBangXH(int type) {
        ArrayList<Entry> bxh = Rank.bangXH[type];
        Entry[] bxhA = new Entry[bxh.size()];
        for (int i = 0; i < bxhA.length; ++i) {
            bxhA[i] = bxh.get(i);
        }
        return bxhA;
    }

    public static String getStringBXH(int type) {
        String str = "";
        switch (type) {
            case 0: {
                if (Rank.bangXH[type].isEmpty()) {
                    str = "Chưa có thông tin";
                    break;
                }
                for (Entry bxh : Rank.bangXH[type]) {
                    str = str + bxh.index + ". " + bxh.name + ": " + Util.getFormatNumber(bxh.nXH[0]) + " yên - cấp: " + bxh.nXH[1] + "\n";
                }
                break;
            }
            case 1: {
                if (Rank.bangXH[type].isEmpty()) {
                    str = "Chưa có thông tin";
                    break;
                }
                if (Rank.bxhCaoThu.size() < 1) {
                    for (Entry bxh : Rank.bangXH[type]) {
                        str = str + bxh.index + ". " + bxh.name + ": " + Util.getFormatNumber(bxh.nXH[0]) + " kinh nghiệm - cấp: " + bxh.nXH[1] + " (" + Server.manager.NinjaS[(int) bxh.nXH[2]] + ")\n";
                    }
                } else {
                    for (Entry2 bxh : Rank.bxhCaoThu) {
                        str = str + bxh.index + ". " + bxh.name + " (" + bxh.nClass + ") đã đạt cấp độ " + bxh.level + " vào lúc " + bxh.time + ".\n";
                    }
                }
                break;
            }
            case 2: {
                if (Rank.bangXH[type].isEmpty()) {
                    str = "Chưa có thông tin";
                    break;
                }
                for (Entry bxh : Rank.bangXH[type]) {
                    ClanManager clan = ClanManager.getClanName(bxh.name);
                    if (clan != null) {
                        str = str + bxh.index + ". Gia tộc " + bxh.name + " trình độ cấp " + bxh.nXH[0] + " do " + clan.getmain_name() + " làm tộc trưởng, thành viên " + clan.members.size() + "/" + clan.getMemMax() + "\n";
                    } else {
                        str = str + bxh.index + ". Gia tộc " + bxh.name + " trình độ cấp " + bxh.nXH[0] + " đã bị giải tán\n";
                    }
                }
                break;
            }
            case 3: {
                if (Rank.bangXH[type].isEmpty()) {
                    str = "Chưa có thông tin";
                    break;
                }
                for (Entry bxh : Rank.bangXH[type]) {
                    str = str + bxh.index + ". " + bxh.name + " nhận được " + Util.getFormatNumber(bxh.nXH[0]) + " " + ItemTemplate.ItemTemplateId((int) bxh.nXH[1]).name + "\n";
                }
                break;
            }
           case 4: {
                if (Rank.bangXH[type].isEmpty()) {
                    str = "Chưa có thông tin";
                    break;
                }
                for (Entry bxh : bangXH[type]) {
                    str += bxh.index + ". " + bxh.name + " Đang có " + bxh.nXH[0] + " Điếm Sự Kiện \n";
                }
                break;
            }
            case 5: {
                if (Rank.bangXH[type].isEmpty()) {
                    str = "Chưa có thông tin";
                    break;
                }
                for (Entry bxh : bangXH[type]) {
                    str += bxh.index + ". " + bxh.name + " : Đang Ở Mốc VIP " + bxh.nXH[0] + "\n";
                }
                break;
            }
            case 6: {
                if (Rank.bangXH[type].isEmpty()) {
                    str = "Chưa có thông tin";
                    break;
                }
                for (Entry bxh : bangXH[type]) {
                    str += bxh.index + ". " + bxh.name + " : Đang Có " + bxh.nXH[0] + " Điểm Nạp\n";
                }
                break;
            }
            case 7: {
                if (Rank.bangXH[type].isEmpty()) {
                    str = "Chưa có thông tin";
                    break;
                }
                for (Entry bxh : bangXH[type]) {
                    str += bxh.index + ". " + bxh.name + " : " + bxh.nXH[0] + " Điểm Hang Động\n";
                }
                break;
            }
        }

        return str;
    }

    public static class Entry {

        int index;
        String name;
        long[] nXH;
    }

    public static class Entry2 {

        int index;
        String name;
        int level;
        String nClass;
        String time;
    }

    public static class Entry3 {

        int index;
        String name;
        int point;
    }

    public static class Entry4 {

        int index;
        String name;
        int point;
    }

    public static void updateCaoThu() {
        Rank.bxhCaoThu.clear();
        ResultSet red = null;
        try {
            synchronized (Rank.bxhCaoThu) {
                int i = 1;
                red = SQLManager.stat.executeQuery("SELECT `name`,`class`,`level`,`time` FROM `xep_hang_level` WHERE `level` = " + Manager.max_level_up + " ORDER BY `id` ASC LIMIT 20;");
                String name;
                int level;
                String nClass;
                String time;
                Entry2 bXHCaoThu;
                if (red != null) {
                    while (red.next()) {
                        name = red.getString("name");
                        level = red.getInt("level");
                        nClass = red.getString("class");
                        time = red.getString("time");
                        bXHCaoThu = new Entry2();
                        bXHCaoThu.name = name;
                        bXHCaoThu.index = i;
                        bXHCaoThu.level = level;
                        bXHCaoThu.nClass = nClass;
                        bXHCaoThu.time = time;
                        bxhCaoThu.add(bXHCaoThu);
                        i++;
                    }
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (red != null) {
                try {
                    red.close();
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }
    }
}
