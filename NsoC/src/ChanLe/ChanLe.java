package ChanLe;

import History.LichSu;
import server.*;
import java.util.ArrayList;
import assembly.Char;
import assembly.Player;
import io.Util;
import stream.Server;

public class ChanLe {
    
    public int totalchan;
    public int totalle;

    public short playerchan;
    public short playerle;

    public long time;
    public long timewait;

    public boolean start;
    public boolean chan;
    public boolean le;

    public long tongrandomcl;
    public String chanorle;

    public ArrayList<Char> teamchan;
    public ArrayList<Char> teamle;
    public int canthiepcl;
    public static short[] CanThiepLe = new short[]{1, 3, 5, 7, 9};
    public static short[] CanThiepChan = new short[]{0, 2, 4, 6, 8};

    // Khởi tạo
    public ChanLe() {
        this.totalchan = 0;
        this.totalle = 0;
        this.playerchan = 0;
        this.playerle = 0;
        this.teamchan = new ArrayList<Char>();
        this.teamle = new ArrayList<Char>();
        this.chan = false;
        this.le = false;
        this.time = 60;
        this.start = true;
    }

    //Thông tin kết quả.
    public void InfoChanLe(Player p) {
        String text = "Tổng tiền cược Chẵn : " + this.totalchan + " Xu \n\n"
                + "Tổng tiền cược Lẻ : " + this.totalle + " Xu \n\n"
                + "Thời gian : " + this.time + " giây\n\n";

        if (chanorle != null) {
            text += "Kết quả phiên trước : " + this.chanorle + " : " + this.tongrandomcl + "\n\n";
        }
        if (!(p.c.chan == false && p.c.le == false)) {
            text += "Bạn đã cược " + p.c.joincl + " xu vào " + p.c.chanle;
        } else {
            text += p.c.chanle;
        }
        Server.manager.sendTB(p, "Chẵn Lẻ", text);
    }

    //random để lấy kết quả
    private void random() {
        long a = Util.nextInt(0, 10);
        ArrayList<Integer> list = new ArrayList<>();

        if (canthiepcl == 1) { // Lẻ
            int at = Util.nextInt(CanThiepLe.length);
            list.add((int) CanThiepLe[at]);
        }

        if (canthiepcl == 2) { // Chẵn
            int at = Util.nextInt(CanThiepChan.length);
            list.add((int) CanThiepChan[at]);
        }

        if (canthiepcl != 0) {
            canthiepcl = 0;
            int index = 0;
            a = list.get(index);
            list.remove(index);
            list.clear();
        }

        this.tongrandomcl = a;

        if (0 <= this.tongrandomcl && this.tongrandomcl <= 10) {
            if (this.tongrandomcl % 2 == 0) {
                this.chanorle = "Chẵn";
                this.chan = true;
                this.le = false;
            } else {
                this.chanorle = "Lẻ";
                this.le = true;
                this.chan = false;
            }
        }

        Manager.serverChat("Chẵn Lẻ Xu", "Kết Quả : " + chanorle + " : Tổng : " + a + " = " + tongrandomcl);
        SoiCaucl.soicau.add(new SoiCaucl("Kết quả : " + chanorle, ": Tổng : " + this.tongrandomcl));
    }

    public void Start() {
        if (this.start == true) {
            if (this.time > 0) {
                this.time -= 1;
            }
            if (this.time == 0) {
                this.start = false;
                this.timewait = 10;
                this.random();
                this.Wait();
            }
        }
    }

    public void Wait() {
        while (this.start == false) {
            if (this.timewait > 0) {
                this.timewait -= 1;
            }
            if (this.chan == true) {
                for (int i = 0; i < this.teamchan.size(); i++) {
                    Char c = this.teamchan.get(i);
                    if (c.joincl > 0) {
                        //LichSu.LichSuTaiXiuLuong(c.name, " Đặt Tài Ăn " + (c.joincl * 18 / 10), c.p.luong, c.p.luong + c.joincl * 18 / 10, +c.joincl * 18 / 10);
                        c.p.c.upxuMessage(c.p.c.joincl * 18 / 10);
                        c.joincl = 0;
                        c.chan = false;
                        c.le = false;
                        this.chan = false;
                        c.datacl();
                    }
                }
                for (int i = 0; i < this.teamle.size(); i++) {
                    Char c = this.teamle.get(i);
                    c.joincl = 0;
                    c.le = false;
                    c.chan = false;
                    this.le = false;
                    c.datacl();
                }
                this.totalchan = 0;
                this.teamchan.clear();
                this.totalle = 0;
                this.teamle.clear();
            }
            if (this.le == true) {
                for (int i = 0; i < this.teamle.size(); i++) {
                    Char c = this.teamle.get(i);
                    if (c.joincl > 0) {
                        //LichSu.LichSuTaiXiuLuong(c.name, " Đặt Xỉu Ăn " + (c.joincl * 18 / 10), c.p.luong, c.p.luong + c.joincl * 18 / 10, +c.joincl * 18 / 10);
                        c.p.c.upxuMessage(c.p.c.joincl * 18 / 10);
                        c.joincl = 0;
                        c.le = false;
                        c.chan = false;
                        this.le = false;
                        c.datacl();
                    }
                }
                for (int i = 0; i < this.teamchan.size(); i++) {
                    Char c = this.teamchan.get(i);
                    c.joincl = 0;
                    c.chan = false;
                    c.le = false;
                    this.chan = false;
                    c.datacl();
                }
                this.totalle = 0;
                this.teamle.clear();
                this.totalchan = 0;
                this.teamchan.clear();
            }
            if (this.timewait == 0) {
                this.time = 60;
                this.start = true;
                this.Start();
            }
        }
    }

    // đặt cược tài
    public void joinChan(Player p, int joinchan) {
        if (this.time <= 10L) {
            p.conn.sendMessageLog("Đã hết thời gian đặt cược.");
            return;
        }
        if (joinchan > p.c.xu || joinchan <= 0 || ((p.c.xu - joinchan) < 0)) {
            p.conn.sendMessageLog("Bạn không đủ xu.");
            return;
        }
        if (p.c.le == true) {
            p.conn.sendMessageLog("Bạn đã đặt lẻ.");
            return;
        }
        this.totalchan += joinchan;
        p.c.joincl += joinchan;
//        LichSu.LichSuTaiXiuLuong(p.c.name, " Đặt Tài Trừ " + (-joinchan), p.luong, p.luong - joinchan, -joinchan);
        p.c.upxuMessage(-joinchan);
        p.c.chan = true;
        p.c.le = false;
        this.teamchan.add(p.c);
        p.c.datacl();
        Server.manager.chanle[0].InfoChanLe(p);
    }

    // đặt cược xỉu
    public void joinle(Player p, int joinle) {
        if (this.time <= 10L) {
            p.conn.sendMessageLog("Đã hết thời gian đặt cược.");
            return;
        }
        if (joinle > p.c.xu || joinle <= 0 || ((p.c.xu - joinle) < 0)) {
            p.conn.sendMessageLog("Bạn không đủ xu.");
            return;
        }
        if (p.c.chan == true) {
            p.conn.sendMessageLog("Bạn đã đặt chẵn.");
            return;
        }
        this.totalle += joinle;
        p.c.joincl += joinle;
//        LichSu.LichSuTaiXiuLuong(p.c.name, " Đặt Xỉu Trừ " + (-joinle), p.luong, p.luong - joinle, -joinle);
        p.c.upxuMessage(-joinle);
        p.c.le = true;
        p.c.chan = false;
        this.teamle.add(p.c);
        p.c.datacl();
        Server.manager.chanle[0].InfoChanLe(p);
    }
}
