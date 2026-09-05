/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package History;

import io.Util;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.time.Instant;
import java.util.Calendar;
import java.util.Date;

/**
 *
 * @author Administrator
 */
public class LichSu {

    public static String DATE_FORMAT_FILE = "dd_MMM_yyyy";
    public static SimpleDateFormat dateFormatFile = null;

    public static void LichSuGiaoDich(String name1, String name2, String itemsend, long xusend, String itemadd, long xuadd) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));
            String filename = "LichSu/LichSuGiaoDich/LichSuGiaoDich_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("----------Thời Gian : " + str + "--------\n- Name : " + name1 + " Giao Dịch Với " + name2 + "\n- Item Chuyển : \n" + itemsend + "\n- Xu Chuyển : " + xusend + "\n- Item Nhận : \n" + itemadd + "\n- Xu Nhận : " + xuadd + "\n-------------\n");
            bw.close();
            fw.close();
        } catch (IOException e) {
            System.out.println(e);
        }
    }

    public static void LichSuMoItemVinhVien(String name1, String item) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));
            String filename = "LichSu/LichSuMoItemVinhVien/LichSuMoItemVinhVien_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("Name : " + name1 + " Mở Được " + item + " Vĩnh Viễn vào lúc " + str + "" + " \n");
            bw.close();
            fw.close();
        } catch (IOException e) {
            System.out.println(e);
        }
    }

    public static void LichSuVeBua(String name1, String item) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));// code by khongminhtien
            String filename = "LichSu/LichSuVeBua/LichSuVeBua_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("Name : " + name1 + " Vẽ Bùa Nhận Được " + item + " Vĩnh Viễn vào lúc " + str + "" + " \n");
            bw.close();
            fw.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    }

    public static void LichSuLuong(String name, int luongcu, int luongmoi, String noidung, long luongthaydoi) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));
            String filename = "LichSu/LichSuLuong/LichSuLuong_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("----------Thời Gian : " + str + "--------\n"
                    + "- Name : " + name + "\n"
                    + "- Tên Giao Dịch : " + noidung + "\n"
                    + "- Lượng Cũ : " + luongcu + "\n"
                    + "- Lượng Mới : " + luongmoi + "\n"
                    + "- Lượng Thay Đổi :" + luongthaydoi + " Lượng \n");
            bw.close();
            fw.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    }

    public static void LichSuXu(String name, long xucu, long xumoi, String noidung, long xuthaydoi) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));
            String filename = "LichSu/LichSuXu/LichSuXu_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("----------Thời Gian : " + str + "--------\n"
                    + "- Name : " + name + "\n"
                    + "- Tên Giao Dịch : " + noidung + "\n"
                    + "- Xu Cũ : " + xucu + "\n"
                    + "- Xu Mới : " + xumoi + "\n"
                    + "- Xu Thay Đổi :" + xuthaydoi + "Xu \n");
            bw.close();
            fw.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    }

    public static void LichSuMuaVatPhamGhoso(String name, String itemmua, int soluongmua, int xumua, int luongmua, int luongcu, int luongmoi, int xucu, int xumoi) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));
            String filename = "LichSu/LichSuMuaVatPhamGhoso/LichSuMuaVatPhamGhoso_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("----------Thời Gian : " + str + "--------\n"
                    + "- Name : " + name + "\n"
                    + "- Tên Giao Dịch :  Mua Vật Phẩm Ghoso \n"
                    + "- Item Mua : " + itemmua + "\n"
                    + "- Số Lượng Mua : " + soluongmua + "\n"
                    + "- Lượng Mua : " + luongmua + "\n"
                    + "- Xu Mua : " + xumua + "\n"
                    + "- Lượng Cũ : " + luongcu + "\n"
                    + "- Lượng Mới : " + luongmoi + "\n"
                    + "- Xu Cũ : " + xucu + "\n"
                    + "- Xu Mới : " + xumoi + "\n");
            bw.close();
            fw.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    }

    public static void LichSuTaiXiuLuong(String name, String noidung, int luongcu, int luongmoi, int luongthaydoi) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));
            String filename = "LichSu/TaiXiu/LichSuTaiXiuLuong/LichSuTaiXiuLuong_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("----------Thời Gian : " + str + "--------\n"
                    + "- Name : " + name + "\n"
                    + "- Nội Dung : " + noidung + "\n"
                    + "- Lượng Cũ : " + luongcu + "\n"
                    + "- Lượng Mới : " + luongmoi + "\n"
                    + "- Lượng Thay Đổi : " + luongthaydoi + "\n");
            bw.close();
            fw.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    }

    public static void LichSuMuaDoShinWa(String name1, Long xubuy, String itemmua, int soluong) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));// nguyễn an
            String filename = "LichSu/Shinwa/LichSuMuaDoShinWa/LichSuMuaDoShinWa_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("Name : " + name1 + " :  Mua " + itemmua + " Từ Shinwa Với Giá " + xubuy + " Xu : Số Lượng " + soluong + " :  Vào " + str + "\n");
            bw.close();
            fw.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    }

    public static void LichSuBanDoShinWa(String name1, String itemsell, long xusell, int soluong) {
        try {
            Calendar calender = Calendar.getInstance();
            Date date = calender.getTime();
            LichSu.dateFormatFile = new SimpleDateFormat(DATE_FORMAT_FILE);
            String str = Util.toDateString(Date.from(Instant.now()));
            String filename = "LichSu/Shinwa/LichSuBanDoShinWa/LichSuBanDoShinWa_" + dateFormatFile.format(date) + ".txt";
            FileWriter fw = new FileWriter(filename, true);
            BufferedWriter bw = new BufferedWriter(fw);
            bw.write("Name : " + name1 + " : Bán " + itemsell + " :  Số Lượng : " + soluong + " Với Giá : " + xusell + " Xu Vào " + str + " \n");
            bw.close();
            fw.close();
        } catch (Exception e) {
            System.out.println(e);
        }
    }
}
