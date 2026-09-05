package TaiXiu;

import History.LichSu;
import server.*;
import java.util.ArrayList;
import assembly.Char;
import assembly.Player;
import io.Util;
import stream.Server;

/**
 *
 * @author Tien
 */
public class TaiXiu {

    public int totalxiu;
    public int totaltai;

    public short playertai;
    public short playerxiu;

    public long time;
    public long timewait;

    public boolean start;
    public boolean tai;
    public boolean xiu;

    public long tongrandom;
    public String taiorxiu;

    public ArrayList<Char> teamtai;
    public ArrayList<Char> teamxiu;
    public int canthiep;

    // Khởi tạo
    public TaiXiu() {
        this.totaltai = 0;
        this.totalxiu = 0;
        this.playertai = 0;
        this.playerxiu = 0;
        this.teamtai = new ArrayList<Char>();
        this.teamxiu = new ArrayList<Char>();
        this.tai = false;
        this.xiu = false;
        this.time = 60;
        this.start = true;
    }

    //Thông tin kết quả.
    public void InfoTaiXiu(Player p) {
        String text = "Tổng tiền cược Tài : " + this.totaltai + " Lượng \n\n"
                + "Tổng tiền cược Xỉu : " + this.totalxiu + " Lượng \n\n"
                + "Thời gian : " + this.time + " giây\n\n";

        if (taiorxiu != null) {
            text += "Kết quả phiên trước : " + this.taiorxiu + " : " + this.tongrandom + "\n\n";
        }
        if (!(p.c.tai == false && p.c.xiu == false)) {
            text += "Bạn đã cược " + p.c.jointx + " lượng vào " + p.c.taixiu;
        } else {
            text += p.c.taixiu;
        }
        Server.manager.sendTB(p, "Tài Xỉu", text);
    }

    //random để lấy kết quả
    private void random() {
        long a = Util.nextInt(1, 6);
        long b = Util.nextInt(1, 6);
        long c = Util.nextInt(1, 6);
        ArrayList<Integer> list = new ArrayList<>();
        if (canthiep == 1) { // xỉu
            int at = Util.nextInt(1, 6);
            int tmp = 9 - at;
            if (tmp > 6) {
                tmp = 6;
            }
            int bt = Util.nextInt(1, tmp);
            tmp = 10 - (at + bt);
            if (tmp > 6) {
                tmp = 6;
            }
            int ct = Util.nextInt(1, tmp);
            list.add(at);
            list.add(bt);
            list.add(ct);
        }

        if (canthiep == 2) { // tài
            int at = Util.nextInt(1, 6);
            int tmp = 5 - at;
            if (tmp < 1) {
                tmp = 1;
            }
            int bt = Util.nextInt(tmp, 6);
            tmp = 11 - (at + bt);
            if (tmp < 1) {
                tmp = 1;
            }
            int ct = Util.nextInt(tmp, 6);
            list.add(at);
            list.add(bt);
            list.add(ct);
        }

        if (canthiep != 0) {
            canthiep = 0;
            int index = Util.nextInt(3);
            a = list.get(index);
            list.remove(index);
            index = Util.nextInt(2);
            b = list.get(index);
            list.remove(index);
            c = list.get(0);
            list.clear();
        }

        this.tongrandom = a + b + c;
        if (3 <= this.tongrandom && this.tongrandom <= 10) {
            this.taiorxiu = "Xỉu";
            this.xiu = true;
            this.tai = false;
        } else if (this.tongrandom > 10) {
            this.taiorxiu = "Tài";
            this.tai = true;
            this.xiu = false;
        }
        Manager.serverChat("Tài Xỉu Lượng", "Kết Quả : " + taiorxiu + " : Tổng : " + a + " + " + b + " + " + c + " = " + tongrandom);
        SoiCau.soicau.add(new SoiCau("Kết quả : " + taiorxiu, ": Tổng : " + this.tongrandom));
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
            if (this.tai == true) {
                for (int i = 0; i < this.teamtai.size(); i++) {
                    Char c = this.teamtai.get(i);
                    if (c.jointx > 0) {
                        LichSu.LichSuTaiXiuLuong(c.name, " Đặt Tài Ăn " + (c.jointx * 18 / 10), c.p.luong, c.p.luong + c.jointx * 18 / 10, +c.jointx * 18 / 10);
                        c.p.upluongMessage(c.p.c.jointx * 18 / 10);
                        c.jointx = 0;
                        c.tai = false;
                        c.xiu = false;
                        this.tai = false;
                        c.datatx();
                    }
                }
                for (int i = 0; i < this.teamxiu.size(); i++) {
                    Char c = this.teamxiu.get(i);
                    c.jointx = 0;
                    c.xiu = false;
                    c.tai = false;
                    this.xiu = false;
                    c.datatx();
                }
                this.totaltai = 0;
                this.teamtai.clear();
                this.totalxiu = 0;
                this.teamxiu.clear();
            }
            if (this.xiu == true) {
                for (int i = 0; i < this.teamxiu.size(); i++) {
                    Char c = this.teamxiu.get(i);
                    if (c.jointx > 0) {
                        LichSu.LichSuTaiXiuLuong(c.name, " Đặt Xỉu Ăn " + (c.jointx * 18 / 10), c.p.luong, c.p.luong + c.jointx * 18 / 10, +c.jointx * 18 / 10);
                        c.p.upluongMessage(c.p.c.jointx * 18 / 10);
                        c.jointx = 0;
                        c.xiu = false;
                        c.tai = false;
                        this.xiu = false;
                        c.datatx();
                    }
                }
                for (int i = 0; i < this.teamtai.size(); i++) {
                    Char c = this.teamtai.get(i);
                    c.jointx = 0;
                    c.tai = false;
                    c.xiu = false;
                    this.tai = false;
                    c.datatx();
                }
                this.totalxiu = 0;
                this.teamxiu.clear();
                this.totaltai = 0;
                this.teamtai.clear();
            }
            if (this.timewait == 0) {
                this.time = 60;
                this.start = true;
                this.Start();
            }
        }
    }

    // đặt cược tài
    public void joinTai(Player p, int jointai) {
        if (this.time <= 10L) {
            p.conn.sendMessageLog("Đã hết thời gian đặt cược.");
            return;
        }
        if (jointai > p.luong || jointai <= 0 || ((p.luong - jointai) < 0)) {
            p.conn.sendMessageLog("Bạn không đủ lượng.");
            return;
        }
        if (p.c.xiu == true) {
            p.conn.sendMessageLog("Bạn đã đặt xỉu.");
            return;
        }
        this.totaltai += jointai;
        p.c.jointx += jointai;
        LichSu.LichSuTaiXiuLuong(p.c.name, " Đặt Tài Trừ " + (-jointai), p.luong, p.luong - jointai, -jointai);
        p.upluongMessage(-jointai);
        p.c.tai = true;
        p.c.xiu = false;
        this.teamtai.add(p.c);
        p.c.datatx();
        Server.manager.taixiu[0].InfoTaiXiu(p);
    }

    // đặt cược xỉu
    public void joinXiu(Player p, int joinxiu) {
        if (this.time <= 10L) {
            p.conn.sendMessageLog("Đã hết thời gian đặt cược.");
            return;
        }
        if (joinxiu > p.luong || joinxiu <= 0 || ((p.luong - joinxiu) < 0)) {
            p.conn.sendMessageLog("Bạn không đủ lượng.");
            return;
        }
        if (p.c.tai == true) {
            p.conn.sendMessageLog("Bạn đã đặt tài.");
            return;
        }
        this.totalxiu += joinxiu;
        p.c.jointx += joinxiu;
        LichSu.LichSuTaiXiuLuong(p.c.name, " Đặt Xỉu Trừ " + (-joinxiu), p.luong, p.luong - joinxiu, -joinxiu);
        p.upluongMessage(-joinxiu);
        p.c.xiu = true;
        p.c.tai = false;
        this.teamxiu.add(p.c);
        p.c.datatx();
        Server.manager.taixiu[0].InfoTaiXiu(p);
    }
}
