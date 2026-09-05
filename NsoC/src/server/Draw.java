package server;

import History.LichSu;
import Item.ItemName;
import Menu.Send;
import stream.Client;
import stream.Server;
import stream.Admin;
import stream.Dun;
import stream.GiaTocChien;
import assembly.Item;
import assembly.Level;
import assembly.Char;
import assembly.ClanManager;
import assembly.Player;
import assembly.Language;
import io.Message;
import io.SQLManager;
import io.Util;
import template.ItemTemplate;
import org.json.simple.JSONArray;
import org.json.simple.JSONValue;

import java.io.IOException;
import java.sql.ResultSet;
import java.time.Instant;
import java.util.Date;

public class Draw {

    public static void Draw(Player p, Message m) throws IOException {
        short menuId = m.reader().readShort();
        String str = m.reader().readUTF();
        m.cleanup();
        //   System.out.println("menuId "+menuId+" str "+str);
        byte b = -1;
        try {
            b = m.reader().readByte();
        } catch (IOException e) {
        }
        m.cleanup();

        switch (menuId) {
            // vòng xoay
            case 100: {
                if (p.status == 1) {
                    p.conn.sendMessageLog("Tài khoản chưa được kích hoạt. Không thể sử dụng chức năng này.");
                    return;
                }
                try {
                    String num = str.replaceAll(" ", "").trim();
                    if (num.length() > 10 || !Util.checkNumInt(num) || b < 0 || b >= Server.manager.rotationluck.length) {
                        return;
                    }
                    if (!Util.isNumeric(num)) {
                        return;
                    }
                    int xujoin = Integer.parseInt(num);
                    Server.manager.rotationluck[b].joinLuck(p, xujoin);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case 101: {
                if (p.status == 1) {
                    p.conn.sendMessageLog("Tài khoản chưa được kích hoạt. Không thể sử dụng chức năng này.");
                    return;
                }
                if (b < 0) {
                    return;
                }
                if (b == 0 && p.c.isTaskDanhVong == 1 && p.c.taskDanhVong[0] == 0 && p.c.taskDanhVong[1] < p.c.taskDanhVong[2]) {
                    p.c.taskDanhVong[1]++;
                }
                if (b == 1 && p.c.isTaskDanhVong == 1 && p.c.taskDanhVong[0] == 1 && p.c.taskDanhVong[1] < p.c.taskDanhVong[2]) {
                    p.c.taskDanhVong[1]++;
                }
                Server.manager.rotationluck[b].luckMessage(p);
                break;
            }
            case 102: {
                p.typemenu = 92;
                Service.doMenuArray(p, new String[]{"Vòng Xoay VIP", "Vòng Xoay Thường"});
                break;
            }
            case 1010:
                try {
                    p.nameUS = str;
                    Char a1 = Client.gI().getNinja(str);
                    if (a1 != null) {
                        Service.sendWrite(p, (short) 1011, "Nhập lượng:");
                    } else {
                        p.sendAddchatYellow("Nhân vật này không tồn tại hoặc không online.");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            case 1011:
                try {
                    p.luongGF = str;
                    Send.sendLuong(p);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            case 1012:
                try {
                    p.nameUS = str;
                    Char a2 = Client.gI().getNinja(str);
                    if (a2 != null) {
                        Service.sendWrite(p, (short) 1013, "Nhập xu:");
                    } else {
                        p.sendAddchatYellow("Nhân vật này không tồn tại hoặc không online.");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            case 1013:
                try {
                    p.xuGF = str;
                    Send.sendXu(p);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            case 1: {
                try {
                    if (p.c.quantityItemyTotal(279) <= 0) {
                        break;
                    }
                    byte n;

                    Char c = Client.gI().getNinja(str);
                    if (c != null && c.tileMap != null && c.tileMap.map != null && !c.tileMap.map.LangCo() && c.tileMap.map.getXHD() == -1 && c.mapid != 111 && c.mapid != 133 && c.mapid != 160 && !c.tileMap.map.mapChienTruong() && !c.tileMap.map.mapLDGT() && !c.tileMap.map.mapBossTuanLoc() && !c.tileMap.map.mapGTC() && !c.tileMap.map.huyenthoai() && !c.tileMap.map.langshiba() && c.mapid != 188 && c.mapid != 112 && c.mapid != 113 && c.mapid != 20) {
                        if (p.c.level < 60 && c.tileMap.map.VDMQ()) {
                            p.conn.sendMessageLog("Trình độ của bạn chưa đủ để di chuyển tới đây");
                            return;
                        }
                        if (p.c.level < 130 && c.tileMap.map.id == 160) {
                            p.conn.sendMessageLog("Trình độ của bạn chưa đủ để di chuyển tới đây");
                            return;
                        }
                        for (n = 0; n < p.c.get().ItemMounts.length; n++) {
                            if (p.c.get().ItemMounts[n] != null && p.c.get().ItemMounts[n].isExpires && p.c.get().ItemMounts[n].expires < System.currentTimeMillis()) {
                                p.conn.sendMessageLog("Thú cưỡi đã hết hạn , không thể sử dụng chức năng này");
                                return;
                            }
                        }
                        if (p.c.tileMap.map.mapGTC() || p.c.tileMap.map.mapChienTruong() || p.c.tileMap.map.id == 111 || p.c.tileMap.map.id == 199 || p.c.tileMap.map.id == 194 || p.c.tileMap.map.id == 195 || p.c.tileMap.map.id == 196 || p.c.tileMap.map.id == 197 || p.c.tileMap.map.id == 198 || p.c.tileMap.map.id == 200 || p.c.tileMap.map.id == 201 || p.c.tileMap.map.id == 202 || p.c.tileMap.map.id == 203 || p.c.tileMap.map.id == 204 || p.c.tileMap.map.id == 205
                                || p.c.tileMap.map.id == 206 || p.c.tileMap.map.id == 207 || p.c.tileMap.map.id == 210 || p.c.tileMap.map.id == 212
                                || p.c.tileMap.map.id == 208 || p.c.tileMap.map.id == 209 || p.c.tileMap.map.id == 211 || p.c.tileMap.map.id == 213 || p.c.tileMap.map.id == 214
                                ) {
                            p.c.typepk = 0;
                            Service.ChangTypePkId(p.c, (byte) 0);
                        }
                        p.c.tileMap.leave(p);
                        p.c.get().x = c.get().x;
                        p.c.get().y = c.get().y;
                        c.tileMap.Enter(p);
                        return;
                    }
                    p.sendAddchatYellow("Vị trí người này không thể đi tới hoặc không online");
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case 2: {
                try {
                    Char temp = Client.gI().getNinja(str);
                    if (temp != null) {
                        Char friendNinja = p.c.tileMap.getNinja(temp.id);
                        if (friendNinja != null && friendNinja.id == p.c.id) {
                            Service.chatNPC(p, (short) 0, Language.NAME_LOI_DAI);
                        } else if (friendNinja != null && friendNinja.id != p.c.id) {
                            p.sendRequestBattleToAnother(friendNinja, p.c);
                            Service.chatNPC(p, (short) 0, Language.SEND_MESS_LOI_DAI);
                        } else {
                            Service.chatNPC(p, (short) 0, Language.NOT_IN_ZONE);
                        }
                    } else {
                        Service.chatNPC(p, (short) 0, "Người chơi này không ở trong cùng khu với con hoặc không tồn tại, ta không thể gửi lời mời!");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case 3: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 100000000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 100 Triệu Xu");
                        return;
                    }
                    long tienCuoc = Long.parseLong(str);
                    if (tienCuoc > p.c.xu || p.c.xu < 1000) {
                        Service.chatNPC(p, (short) 37, "Con không đủ xu để đặt cược");
                        break;
                    }
                    if (tienCuoc < 1000 || tienCuoc % 50 != 0) {
                        Service.chatNPC(p, (short) 37, "Xu cược phải lớn hơn 1000 xu và chia hết cho 50");
                        break;
                    }
                    Dun dun = null;
                    if (p.c.dunId != -1) {
                        if (Dun.duns.containsKey(p.c.dunId)) {
                            dun = Dun.duns.get(p.c.dunId);
                        }
                    }
                    if (dun != null) {
                        if (dun.c1.id == p.c.id) {
                            if (dun.tienCuocTeam2 != 0 && dun.tienCuocTeam2 != tienCuoc) {
                                Service.chatNPC(p, (short) 37, "Đối thủ của con đã đặt cược " + Util.getFormatNumber(dun.tienCuocTeam2) + " xu con hãy đặt lại đi!");
                                return;
                            }
                            if (dun.tienCuocTeam1 != 0) {
                                Service.chatNPC(p, (short) 37, "Con đã đặt cược trước đó rồi.");
                                return;
                            }
                            dun.tienCuocTeam1 = tienCuoc;
                            LichSu.LichSuXu(p.c.name, p.c.xu, p.c.xu - (int) tienCuoc, " Đặt Cược Lôi Đài", -(int) tienCuoc);
                            p.c.upxuMessage(-tienCuoc);
                            Service.chatNPC(p, (short) 37, "Con đã đặt cược " + dun.tienCuocTeam1 + " xu");
                            dun.c2.p.sendAddchatYellow("Người chơi " + dun.c1.name + " đã được cược " + Util.getFormatNumber(dun.tienCuocTeam1) + " xu.");
                        } else if (dun.c2.id == p.c.id) {
                            if (dun.tienCuocTeam1 != 0 && dun.tienCuocTeam1 != tienCuoc) {
                                Service.chatNPC(p, (short) 37, "Đối thủ của con đã đặt cược " + Util.getFormatNumber(dun.tienCuocTeam1) + " xu con hãy đặt lại đi!");
                                return;
                            }
                            if (dun.tienCuocTeam2 != 0) {
                                Service.chatNPC(p, (short) 37, "Con đã đặt cược trước đó rồi.");
                                return;
                            }
                            dun.tienCuocTeam2 = tienCuoc;
                            LichSu.LichSuXu(p.c.name, p.c.xu, p.c.xu - (int) tienCuoc, " Đặt Cược Lôi Đài", -(int) tienCuoc);
                            p.c.upxuMessage(-tienCuoc);
                            Service.chatNPC(p, (short) 37, "Con đã đặt cược " + Util.getFormatNumber(dun.tienCuocTeam2) + " xu");
                            dun.c1.p.sendAddchatYellow("Người chơi " + dun.c2.name + " đã được cược " + Util.getFormatNumber(dun.tienCuocTeam2) + " xu.");
                        }
                        if (dun.tienCuocTeam1 != 0 && dun.tienCuocTeam2 != 0 && dun.tienCuocTeam1 == dun.tienCuocTeam2 && dun.team1.size() > 0 && dun.team2.size() > 0) {
                            if (dun.tienCuocTeam1 >= 1000000L) {
                                Manager.serverChat("Server: ", "Người chơi " + dun.c1.name + " (" + dun.c1.level + ")"
                                        + " đang thách đấu với " + dun.c2.name + " (" + dun.c2.level + "): " + Util.getFormatNumber(dun.tienCuocTeam1) + " xu tại lôi đài, hãy mau mau đến xem và cổ vũ.");
                            }
                            dun.startDun();
                        }
                    } else {
                        return;
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }

            //gift code
            case 4: {
                String check = str.replaceAll("\\s+", "");
                if (check.equals("")) {
                    p.conn.sendMessageLog("Mã Gift code nhập vào không hợp lệ.");
                    break;
                }
                if (!Util.CheckString(check, "^[a-zA-Z0-9]+$")) {
                    p.conn.sendMessageLog("Mã Gift code nhập vào không hợp lệ.");
                    break;
                }
                check = check.toUpperCase();
                try {
                    synchronized (Server.LOCK_MYSQL) {
                        ResultSet red = SQLManager.stat.executeQuery("SELECT * FROM `gift_code` WHERE `code` LIKE '" + check + "';");
                        if (red != null && red.first()) {
                            int id = red.getInt("id");
                            String code = red.getString("code");
                            JSONArray jar = (JSONArray) JSONValue.parse(red.getString("item_id"));
                            if (p.c.getBagNull() < jar.size()) {
                                p.conn.sendMessageLog(Language.NOT_ENOUGH_BAG);
                                break;
                            }
                            int j;
                            int[] itemId = new int[jar.size()];
                            for (j = 0; j < jar.size(); j++) {
                                itemId[j] = Integer.parseInt(jar.get(j).toString());
                            }
                            jar = (JSONArray) JSONValue.parse(red.getString("item_quantity"));
                            long[] itemQuantity = new long[jar.size()];
                            for (j = 0; j < jar.size(); j++) {
                                itemQuantity[j] = Long.parseLong(jar.get(j).toString());
                            }
                            jar = (JSONArray) JSONValue.parse(red.getString("item_isLock"));
                            byte[] itemIsLock = new byte[jar.size()];
                            for (j = 0; j < jar.size(); j++) {
                                itemIsLock[j] = Byte.parseByte(jar.get(j).toString());
                            }
                            jar = (JSONArray) JSONValue.parse(red.getString("item_expires"));
                            long[] itemExpires = new long[jar.size()];
                            for (j = 0; j < jar.size(); j++) {
                                itemExpires[j] = Long.parseLong(jar.get(j).toString());
                            }

                            int isPlayer = red.getInt("isPlayer");
                            int isTime = red.getInt("isTime");
                            if (isPlayer == 1) {
                                jar = (JSONArray) JSONValue.parse(red.getString("player"));
                                boolean checkUser = false;
                                for (j = 0; j < jar.size(); j++) {
                                    if (jar.get(j).toString().equals(p.username)) {
                                        checkUser = true;
                                        break;
                                    }
                                }
                                if (!checkUser) {
                                    p.conn.sendMessageLog("Bạn không thể sử dụng mã Gift Code này.");
                                    red.close();
                                    break;
                                }
                            }
                            if (isTime == 1) {
                                if (Date.from(Instant.now()).compareTo(Util.getDate(red.getString("time"))) > 0) {
                                    p.conn.sendMessageLog("Mã Gift code này đã hết hạn sử dụng.");
                                    red.close();
                                    break;
                                }
                            }
                            red.close();
                            red = SQLManager.stat.executeQuery("SELECT * FROM `history_gift` WHERE `player_id` = " + p.id + " AND `code` = '" + code + "';");
                            if (red != null && red.first()) {
                                p.conn.sendMessageLog("Bạn đã sử dụng mã Gift code này rồi.");
                            } else {
                                if (itemId.length == itemQuantity.length) {
                                    ItemTemplate data2;
                                    int i;
                                    for (i = 0; i < itemId.length; i++) {
                                        switch (itemId[i]) {
                                            case -3:
                                                p.c.upyenMessage(itemQuantity[i]);
                                                break;
                                            case -2:
                                                LichSu.LichSuXu(p.c.name, p.c.xu, p.c.xu + (int) itemQuantity[i], " Nhập GIFTCODE " + check, +(int) itemQuantity[i]);
                                                p.c.upxuMessage(itemQuantity[i]);
                                                break;
                                            case -1:
                                                LichSu.LichSuLuong(p.c.name, p.luong, (int) (p.luong + itemQuantity[i]), " Nhập GiftCode : " + code, +itemQuantity[i]);
                                                p.upluongMessage(itemQuantity[i]);
                                                break;
                                            default:
                                                data2 = ItemTemplate.ItemTemplateId(itemId[i]);
                                                if (data2 != null) {
                                                    Item itemup;
                                                    if (data2.type < 10) {
                                                        if (data2.type == 1) {
                                                            itemup = ItemTemplate.itemDefault(itemId[i]);
                                                            itemup.sys = GameSrc.SysClass(data2.nclass);
                                                        } else {
                                                            byte sys = (byte) Util.nextInt(1, 3);
                                                            itemup = ItemTemplate.itemDefault(itemId[i], sys);
                                                        }
                                                    } else {
                                                        itemup = ItemTemplate.itemDefault(itemId[i]);
                                                    }
                                                    itemup.quantity = (int) itemQuantity[i];
                                                    if (itemIsLock[i] == 0) {
                                                        itemup.isLock = false;
                                                    } else {
                                                        itemup.isLock = true;
                                                    }
                                                    if (itemExpires[i] != -1) {
                                                        itemup.isExpires = true;
                                                        itemup.expires = System.currentTimeMillis() + itemExpires[i];
                                                    } else {
                                                        itemup.isExpires = false;
                                                    }
                                                    p.c.addItemBag(true, itemup);
                                                }
                                                break;
                                        }
                                    }
                                    String sqlSET = "(" + p.id + ", '" + code + "', '" + Util.toDateString(Date.from(Instant.now())) + "', '" + Util.toDateString(Date.from(Instant.now())) + "', '" + Util.toDateString(Date.from(Instant.now())) + "');";
                                    SQLManager.stat.executeUpdate("INSERT INTO `history_gift` (`player_id`,`code`,`time`, `created_at`, `updated_at`) VALUES " + sqlSET);
                                } else {
                                    p.conn.sendMessageLog("Lỗi xác nhận mã Gift code. Hãy liên hệ Admin để biết thêm chi tiết.");
                                }
                            }
                            jar.clear();
                            red.close();
                            break;
                        } else {
                            p.conn.sendMessageLog("Mã Gift code này đã được sử dụng hoặc không tồn tại.");
                            red.close();
                            break;
                        }
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                }
                break;
            }

            //Mời gia tộc chiến
            case 5: {
                try {
                    ClanManager temp = ClanManager.getClanName(str);
                    ClanManager temp2 = ClanManager.getClanName(p.c.clan.clanName);
                    if (temp != null) {
                        String tocTruong = temp.getmain_name();
                        Char _charTT = Client.gI().getNinja(tocTruong);
                        if (_charTT != null && _charTT.id == p.c.id) {
                            Service.chatNPC(p, (short) 32, "Ngươi muốn thách đấu gia tộc của chính mình à.");
                        } else if (_charTT != null && _charTT.id != p.c.id) {
//                                if (temp.gtcID != -1 && temp.gtcClanName != null) {
//                                    Service.chatNPC(p, (short) 32, "Gia tộc này đang có lời mời từ gia tộc khác");
//                                    return;
//                                }
                            Service.startYesNoDlg(_charTT.p, (byte) 4, "Gia tộc " + p.c.clan.clanName + " muốn thách đấu với gia tộc của bạn. Bạn có đồng ý?");
                            GiaTocChien giaTocChien = new GiaTocChien();
                            temp.gtcID = giaTocChien.gtcID;
                            temp.gtcClanName = p.c.clan.clanName;
                            temp2.gtcID = giaTocChien.gtcID;
                            temp2.gtcClanName = str;
                            Service.chatNPC(p, (short) 32, "Ta đã gửi lời mời thách đấu tới gia tộc " + str);
                        } else {
                            Service.chatNPC(p, (short) 32, "Tộc trưởng gia tộc đối phương không online hoặc không tồn tại. Không thể gửi lời mời.");
                        }
                    } else {
                        Service.chatNPC(p, (short) 32, "Gia tộc này không tồn tại, ta không thể gửi lời mời!");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            // đổi Ngôi sao nhỏ
            case 2024: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        //thông báo
                        p.conn.sendMessageLog("Không hợp lệ");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(415) >= 10 * soluong) {
                        if (p.c.yen < 10000 * soluong) {
                            p.conn.sendMessageLog("Không đủ xu để đổi");
                            return;
                        }
                        if (p.c.xu < 10000 * soluong) {
                            p.conn.sendMessageLog("Không đủ yên để đổi");
                            return;
                        }
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(415, (int) (10 * soluong));
                            // p.hisxu(p.c.xu, (p.c.xu - 10000 * soluong), " Làm Ngôi Sao Nhỏ ", - 10000 * soluong);
                            p.c.upxuMessage(-(10000 * soluong));
                            p.c.upyenMessage(-(10000 * soluong));
                            Item it = ItemTemplate.itemDefault(417);
                            it.quantity = (int) (1 * soluong);
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }

            case -1: { // tre xanh
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(590) >= 10 * soluong) {
                        if (p.c.xu < 10000 * soluong) {
                            p.conn.sendMessageLog("Không đủ Xu");
                            return;
                        }
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(590, (int) (10 * soluong));
                            //p.hisxu(p.c.xu, (p.c.xu - 10000 * soluong), " Làm tre xanh ", - 10000 * soluong);
                            p.c.upxuMessage(-(10000 * soluong));
                            Item it = ItemTemplate.itemDefault(592);
                            it.quantity = (int) (1 * soluong);
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 36, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -2: { // tre vàng
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(591) >= 10 * soluong) {
                        if (p.luong < 10 * soluong) {
                            p.conn.sendMessageLog("Không đủ Lượng");
                            return;
                        }
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(591, (int) (10 * soluong));
                            p.upluongMessage(-(10 * soluong));
                            Item it = ItemTemplate.itemDefault(593);
                            it.quantity = (int) (1 * soluong);
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 36, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -3: { // mẫm lễ vật vàng
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(527) >= 1 * soluong
                            && p.c.quantityItemyTotal(528) >= 1 * soluong
                            && p.c.quantityItemyTotal(529) >= 1 * soluong) {
                        if (p.c.xu < 20000 * soluong) {
                            p.conn.sendMessageLog("Không đủ Xu");
                            return;
                        }
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(527, (int) (1 * soluong));
                            p.c.removeItemBags(528, (int) (1 * soluong));
                            p.c.removeItemBags(529, (int) (1 * soluong));
                            // p.hisxu(p.c.xu, (p.c.xu - 20000 * soluong), " Làm mâm lễ vật vàng ", - 20000 * soluong);
                            p.c.upxuMessage(-(20000 * soluong));
                            Item it = ItemTemplate.itemDefault(534);
                            it.quantity = (int) (1 * soluong);
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -4: { /// mẫm lễ vật bạc
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(530) >= 5 * soluong) {
                        if (p.c.yen < 50000 * soluong) {
                            p.conn.sendMessageLog("Không đủ Yên");
                            return;
                        }
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(530, (int) (5 * soluong));
                            p.c.upyenMessage(-(50000 * soluong));
                            Item it = ItemTemplate.itemDefault(533);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -5: { // Làm Diều Giấy
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(428) >= 5 * soluong
                            && p.c.quantityItemyTotal(429) >= 5 * soluong
                            && p.c.quantityItemyTotal(430) >= 5 * soluong
                            && p.c.quantityItemyTotal(431) >= 5 * soluong
                            && p.c.quantityItemyTotal(432) >= 1 * soluong) {
                        if (p.c.yen < 50000 * soluong) {
                            p.conn.sendMessageLog("Không đủ Yên");
                            return;
                        }
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(428, (int) (5 * soluong));
                            p.c.removeItemBags(429, (int) (5 * soluong));
                            p.c.removeItemBags(430, (int) (5 * soluong));
                            p.c.removeItemBags(431, (int) (5 * soluong));
                            p.c.removeItemBags(432, (int) (1 * soluong));
                            p.c.upyenMessage(-(50000 * soluong));
                            Item it = ItemTemplate.itemDefault(434);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -6: { // Làm Diều Vải
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(428) >= 5 * soluong
                            && p.c.quantityItemyTotal(429) >= 5 * soluong
                            && p.c.quantityItemyTotal(430) >= 5 * soluong
                            && p.c.quantityItemyTotal(431) >= 5 * soluong
                            && p.c.quantityItemyTotal(433) >= 1 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(428, (int) (5 * soluong));
                            p.c.removeItemBags(429, (int) (5 * soluong));
                            p.c.removeItemBags(430, (int) (5 * soluong));
                            p.c.removeItemBags(431, (int) (5 * soluong));
                            p.c.removeItemBags(433, (int) (1 * soluong));
                            Item it = ItemTemplate.itemDefault(435);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -7: { // Đổi Cúp Bạc
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(783) >= 1 * soluong
                            && p.c.quantityItemyTotal(782) >= 1 * soluong) { // bóng
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.xu < 10000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Xu");
                        } else {
                            p.c.removeItemBags(783, (int) (1 * soluong));
                            p.c.removeItemBags(782, (int) (1 * soluong));
                            p.c.upxuMessage(-(10000 * soluong));
                            Item it = ItemTemplate.itemDefault(784);
                            it.quantity = (int) (2 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -55: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.BO) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.KEM) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.DUONG_BOT) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.DAU_TAY) >= 2 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(ItemName.BO, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.KEM, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.DUONG_BOT, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.DAU_TAY, (int) (2 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.BANH_KHUC_CAY_DAU_TAY);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -56: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.BO) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.KEM) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.DUONG_BOT) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.CHOCOLATE) >= 1 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(ItemName.BO, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.KEM, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.DUONG_BOT, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.CHOCOLATE, (int) (1 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.BANH_KHUC_CAY_CHOCOLATE);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -8: { // Đổi Cúp Vàng
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(784) >= 5 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.luong < 10 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(784, (int) (5 * soluong));
                            p.upluongMessage(-(10 * soluong));
                            Item it = ItemTemplate.itemDefault(785);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -9: { // Kẹo
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(459) >= 1 * soluong
                            && p.c.quantityItemyTotal(460) >= 1 * soluong
                            && p.c.quantityItemyTotal(461) >= 1 * soluong) {

                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.luong < 10 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(459, (int) (1 * soluong));
                            p.c.removeItemBags(460, (int) (1 * soluong));
                            p.c.removeItemBags(461, (int) (1 * soluong));
                            p.upluongMessage(-(10 * soluong));
                            Item it = ItemTemplate.itemDefault(465);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -10: { // Làm bánh thập cẩm
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(292) >= 1 * soluong
                            && p.c.quantityItemyTotal(293) >= 1 * soluong
                            && p.c.quantityItemyTotal(295) >= 1 * soluong
                            && p.c.quantityItemyTotal(294) >= 1 * soluong
                            && p.c.quantityItemyTotal(297) >= 1 * soluong) {

                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.yen < 10000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Yên");
                        } else {
                            p.c.removeItemBags(292, (int) (1 * soluong));
                            p.c.removeItemBags(293, (int) (1 * soluong));
                            p.c.removeItemBags(295, (int) (1 * soluong));
                            p.c.removeItemBags(294, (int) (1 * soluong));
                            p.c.removeItemBags(297, (int) (1 * soluong));
                            p.c.upyenMessage(-(10000 * soluong));
                            Item it = ItemTemplate.itemDefault(298);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -11: { // Làm bánh dẻo
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(292) >= 1 * soluong
                            && p.c.quantityItemyTotal(295) >= 1 * soluong
                            && p.c.quantityItemyTotal(294) >= 1 * soluong
                            && p.c.quantityItemyTotal(297) >= 1 * soluong) {

                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.yen < 10000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Yên");
                        } else {
                            p.c.removeItemBags(292, (int) (1 * soluong));
                            p.c.removeItemBags(295, (int) (1 * soluong));
                            p.c.removeItemBags(294, (int) (1 * soluong));
                            p.c.removeItemBags(297, (int) (1 * soluong));
                            p.c.upyenMessage(-(10000 * soluong));
                            Item it = ItemTemplate.itemDefault(299);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -12: { // Làm bánh đậu xanh
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(292) >= 1 * soluong
                            && p.c.quantityItemyTotal(293) >= 1 * soluong
                            && p.c.quantityItemyTotal(294) >= 1 * soluong
                            && p.c.quantityItemyTotal(296) >= 1 * soluong) {

                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.yen < 10000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Yên");
                        } else {
                            p.c.removeItemBags(292, (int) (1 * soluong));
                            p.c.removeItemBags(293, (int) (1 * soluong));
                            p.c.removeItemBags(294, (int) (1 * soluong));
                            p.c.removeItemBags(296, (int) (1 * soluong));
                            p.c.upyenMessage(-(10000 * soluong));
                            Item it = ItemTemplate.itemDefault(300);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -13: { // Làm bánh pia
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(292) >= 1 * soluong
                            && p.c.quantityItemyTotal(293) >= 1 * soluong
                            && p.c.quantityItemyTotal(294) >= 1 * soluong
                            && p.c.quantityItemyTotal(296) >= 1 * soluong) {

                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.yen < 10000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Yên");
                        } else {
                            p.c.removeItemBags(292, (int) (1 * soluong));
                            p.c.removeItemBags(293, (int) (1 * soluong));
                            p.c.removeItemBags(294, (int) (1 * soluong));
                            p.c.removeItemBags(296, (int) (1 * soluong));
                            p.c.upyenMessage(-(10000 * soluong));
                            Item it = ItemTemplate.itemDefault(301);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -14: { // Làm hộp bánh thường
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(298) >= 1 * soluong
                            && p.c.quantityItemyTotal(299) >= 1 * soluong
                            && p.c.quantityItemyTotal(300) >= 1 * soluong
                            && p.c.quantityItemyTotal(301) >= 1 * soluong) {

                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.xu < 50000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Xu");
                        } else {
                            p.c.removeItemBags(298, (int) (1 * soluong));
                            p.c.removeItemBags(299, (int) (1 * soluong));
                            p.c.removeItemBags(300, (int) (1 * soluong));
                            p.c.removeItemBags(301, (int) (1 * soluong));
                            p.c.upxuMessage(-(50000 * soluong));
                            Item it = ItemTemplate.itemDefault(302);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -15: { // Làm hộp bánh thượng hạng
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(298) >= 1 * soluong
                            && p.c.quantityItemyTotal(299) >= 1 * soluong
                            && p.c.quantityItemyTotal(300) >= 1 * soluong
                            && p.c.quantityItemyTotal(301) >= 1 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.luong < 50 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(298, (int) (1 * soluong));
                            p.c.removeItemBags(299, (int) (1 * soluong));
                            p.c.removeItemBags(300, (int) (1 * soluong));
                            p.c.removeItemBags(301, (int) (1 * soluong));
                            p.upluongMessage(-(50 * soluong));
                            Item it = ItemTemplate.itemDefault(303);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -16: { // Làm bó hoa hồng đỏ
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(874) >= 10 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.luong < 1000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(874, (int) (10 * soluong));
                            p.upluongMessage(-(1000 * soluong));
                            Item it = ItemTemplate.itemDefault(877);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -17: { // Làm bó hoa hồng vàng
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(875) >= 10 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.xu < 1000000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Xu");
                        } else {
                            p.c.removeItemBags(875, (int) (10 * soluong));
                            p.c.upxuMessage(-(1000000 * soluong));
                            Item it = ItemTemplate.itemDefault(878);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -18: { // Làm bó hoa hồng xanh
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(876) >= 10 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.yen < 1000000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Yên");
                        } else {
                            p.c.removeItemBags(876, (int) (10 * soluong));
                            p.c.upyenMessage(-(1000000 * soluong));
                            Item it = ItemTemplate.itemDefault(879);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Hành trang của con không có đủ nguyên liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -19: { // Làm Kẹo Táo
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.QUA_TAO) >= 1 * soluong && p.c.quantityItemyTotal(ItemName.MAT_ONG) >= 1) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                            return;
                        }
                        if (p.luong < 20 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(ItemName.QUA_TAO, (int) (1 * soluong));
                            p.c.removeItemBags(ItemName.MAT_ONG, (int) (1 * soluong));

                            p.upluongMessage(-(20 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.KEO_TAO);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -20: { // Làm Hộp Ma Qủy
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.XUONG_THU) >= 1 * soluong && p.c.quantityItemyTotal(ItemName.TAN_LINH) >= 1) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                            return;
                        }
                        if (p.luong < 50 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(ItemName.XUONG_THU, (int) (1 * soluong));
                            p.c.removeItemBags(ItemName.TAN_LINH, (int) (1 * soluong));
                            p.upluongMessage(-(50 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.HOP_MA_QUY);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }

            case -21: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.DOT_TRE_XANH) >= 10 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.xu < 30000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Xu");
                        } else {
                            p.c.removeItemBags(ItemName.DOT_TRE_XANH, (int) (10 * soluong));
                            p.c.upxuMessage(-(30000 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.TRE_XANH_TRAM_DOT);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
           case -22: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.TRE_XANH_TRAM_DOT) >= 3 * soluong && p.c.quantityItemyTotal(ItemName.TIN_VAT) >= 10 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.luong < 50 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(ItemName.TRE_XANH_TRAM_DOT, (int) (3 * soluong));
                            p.c.removeItemBags(ItemName.TIN_VAT, (int) (10 * soluong));
                            p.upluongMessage(-(50 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.TRE_VANG_TRAM_DOT);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -29: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.MIENG_DUA_HAU) >= 10 * soluong ) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.xu < 100000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Xu");
                        } else {
                            p.c.removeItemBags(ItemName.MIENG_DUA_HAU, (int) (10 * soluong));
                            p.c.upxuMessage(-(100000 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.DUA_HAU_DAI);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -30: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.MIENG_DUA_HAU) >= 10 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.luong < 50 * soluong) {
                            p.conn.sendMessageLog("Không Đủ lượng");
                        } else {
                            p.c.removeItemBags(ItemName.MIENG_DUA_HAU, (int) (10 * soluong));
                            p.upluongMessage(-(50 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.DUA_HAU_TRON);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
			
            case -31: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.HOA_SEN_TRANG) >= 10 * soluong ) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        }
                        if (p.c.xu < 100000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Xu");
                        } else {
                            p.c.removeItemBags(ItemName.HOA_SEN_TRANG, (int) (10 * soluong));
                            p.c.upxuMessage(-(100000 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.BO_SEN_TRANG);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -32: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.HOA_SEN_HONG) >= 10 * soluong &&
                            p.c.quantityItemyTotal(ItemName.MAU_NHUOM) >= 1 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                        } else {
                            p.c.removeItemBags(ItemName.HOA_SEN_HONG, (int) (10 * soluong));
                            p.c.removeItemBags(ItemName.MAU_NHUOM, (int) (1 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.BO_SEN_HONG);
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            
            case -59: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.LA_DONG) >= 2 * soluong &&
                            p.c.quantityItemyTotal(ItemName.NEP) >= 2 * soluong &&
                            p.c.quantityItemyTotal(ItemName.DAU_XANH_1) >= 2 * soluong &&
                            p.c.quantityItemyTotal(ItemName.LAT_TRE) >= 2 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                            return;
                        }
                        if (p.c.xu < 50000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Xu");
                        } else {
                            p.c.removeItemBags(ItemName.LA_DONG, (int) (2 * soluong));
                            p.c.removeItemBags(ItemName.NEP, (int) (2 * soluong));
                            p.c.removeItemBags(ItemName.DAU_XANH_1, (int) (2 * soluong));
                            p.c.removeItemBags(ItemName.LAT_TRE, (int) (2 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.BANH_TET);
                            p.c.upxuMessage(-(50000 * soluong));
                            it.quantity = (int) (1 * soluong);
                            it.isLock = false;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -60: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.LA_DONG) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.NEP) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.DAU_XANH_1) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.LAT_TRE) >= 5 * soluong &&
                            p.c.quantityItemyTotal(ItemName.THIT_HEO) >= 3 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                            return;
                        } 
                        if (p.luong < 20 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(ItemName.LA_DONG, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.NEP, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.DAU_XANH_1, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.LAT_TRE, (int) (5 * soluong));
                            p.c.removeItemBags(ItemName.THIT_HEO, (int) (3 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.BANH_CHUNG);
                            p.upluongMessage(-(20 * soluong));
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case -61: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 10000) {
                        p.conn.sendMessageLog("Tối Đa 10000 Cái 1 Lần");
                        return;
                    }
                    long soluong = Integer.parseInt(str);
                    if (p.c.quantityItemyTotal(ItemName.MANH_PHAO_HOA) >= 5 * soluong) {
                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                            return;
                        } 
                        if (p.c.xu < 100000 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Xu");
                            return;
                        }
                        if (p.luong < 150 * soluong) {
                            p.conn.sendMessageLog("Không Đủ Lượng");
                        } else {
                            p.c.removeItemBags(ItemName.MANH_PHAO_HOA, (int) (5 * soluong));
                            Item it = ItemTemplate.itemDefault(ItemName.TRANG_PHAO);
                            p.upluongMessage(-(150 * soluong));
                            p.c.upxuMessage(-(100000 * soluong));
                            it.quantity = (int) (1 * soluong);
                            it.isLock = true;
                            p.c.addItemBag(true, it);
                        }
                        return;
                    } else {
                        Service.chatNPC(p, (short) 33, "Không Đủ Nguyên Liệu");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }

            //Đặt cược gia tộc chiến
            case 8: {
                try {
                    String check = str.replaceAll("\\s+", "");
                    if (!Util.isNumericLong(str) || check.equals("") || !Util.isNumericInt(str)) {
                        Service.chatNPC(p, (short) 37, "Giá trị tiền cược nhập vào không đúng");
                        break;
                    }
                    long tienCuoc = Long.parseLong(str);
                    ClanManager clanManager = ClanManager.getClanName(p.c.clan.clanName);
                    if (tienCuoc > clanManager.coin || clanManager.coin < 1000) {
                        Service.chatNPC(p, (short) 40, "Gia tộc của con không đủ ngân sách để đặt cược.");
                        break;
                    }
                    if (tienCuoc < 1000 || tienCuoc % 50 != 0) {
                        Service.chatNPC(p, (short) 40, "Xu cược phải lớn hơn 1000 xu và chia hết cho 50");
                        break;
                    }
                    GiaTocChien gtc = null;
                    if (clanManager.gtcID != -1) {
                        if (GiaTocChien.gtcs.containsKey(clanManager.gtcID)) {
                            gtc = GiaTocChien.gtcs.get(clanManager.gtcID);
                        }
                    }
                    if (gtc != null) {
                        if (gtc.clan1.id == clanManager.id) {
                            if (gtc.tienCuoc2 != 0 && gtc.tienCuoc2 != tienCuoc) {
                                Service.chatNPC(p, (short) 40, "Gia tộc đối thủ của con đã đặt cược " + Util.getFormatNumber(gtc.tienCuoc2) + " xu con hãy đặt lại đi!");
                                return;
                            }
                            if (gtc.tienCuoc1 != 0) {
                                Service.chatNPC(p, (short) 37, "Gia tộc của con đã đặt cược trước đó rồi.");
                                return;
                            }

                            gtc.tienCuoc1 = tienCuoc;
                            clanManager.coin -= tienCuoc;
                            Service.chatNPC(p, (short) 40, "Con đã đặt cược " + gtc.tienCuoc1 + " xu");
                            if (gtc.gt2.size() > 0) {
                                for (int i = 0; i < gtc.gt2.size(); i++) {
                                    gtc.gt2.get(i).p.sendAddchatYellow("Gia tộc " + clanManager.name + " đã được cược " + Util.getFormatNumber(gtc.tienCuoc1) + " xu.");
                                }
                            }

                        } else if (gtc.clan2.id == clanManager.id) {
                            if (gtc.tienCuoc1 != 0 && gtc.tienCuoc1 != tienCuoc) {
                                Service.chatNPC(p, (short) 40, "Gia tộc đối thủ của con đã đặt cược " + Util.getFormatNumber(gtc.tienCuoc1) + " xu con hãy đặt lại đi!");
                                return;
                            }
                            if (gtc.tienCuoc2 != 0) {
                                Service.chatNPC(p, (short) 40, "Con đã đặt cược trước đó rồi.");
                                return;
                            }

                            gtc.tienCuoc2 = tienCuoc;
                            clanManager.coin -= tienCuoc;
                            Service.chatNPC(p, (short) 40, "Con đã đặt cược " + gtc.tienCuoc2 + " xu");
                            if (gtc.gt1.size() > 0) {
                                for (int i = 0; i < gtc.gt1.size(); i++) {
                                    gtc.gt1.get(i).p.sendAddchatYellow("Gia tộc " + clanManager.name + " đã được cược " + Util.getFormatNumber(gtc.tienCuoc2) + " xu.");
                                }
                            }
                        }

                        if (gtc.tienCuoc1 != 0 && gtc.tienCuoc2 != 0 && gtc.tienCuoc1 == gtc.tienCuoc2 && gtc.gt1.size() > 0 && gtc.gt2.size() > 0) {
                            gtc.invite();
                        }
                    } else {
                        return;
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            //Đổi coin => lượng
            case 9: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 1000000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 1.000.000 1 Lần");
                        return;
                    }
                    long coin = Integer.parseInt(str);
                    ResultSet red = SQLManager.stat.executeQuery("SELECT `coin` FROM `player` WHERE `id` = " + p.id + ";");
                    if (red != null && red.first()) {
                        int coinP = Integer.parseInt(red.getString("coin"));
                        int pre_gold = p.luong;
                        int pre_xu = p.c.xu;
                        int pre_yen = p.c.yen;
                        long pre_diamond = coinP;
                        if (coin <= coinP) {
                            coinP -= coin;
                            p.upluongMessage(coin);
                            SQLManager.stat.executeUpdate("UPDATE `player` SET `coin`=" + coinP + " WHERE `id`=" + p.id + " LIMIT 1;");
                            SQLManager.stat.executeUpdate("INSERT INTO transfer(`userid`,`cointruoc`,`coinsau`,`luongtruoc`,`luongsau`,`xutruoc`,`xusau`,`yentruoc`,`yensau`,`soluong`,`time`,`created_at`) VALUES (" + p.id + "," + pre_diamond + "," + coinP + "," + pre_gold + "," + p.luong + "," + pre_xu + "," + p.c.xu + "," + pre_yen + "," + p.c.yen + "," + coin + "," + (System.currentTimeMillis() / 1000L) + ",'" + Util.toDateString(Date.from(Instant.now())) + "');");
                        } else {
                            p.conn.sendMessageLog("Bạn không đủ coin để đổi ra lượng.");
                        }
                        p.flush();
                        red.close();
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            //Đổi coin => xu
            case 10: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 1000000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 1.000.000 1 Lần");
                        return;
                    }
                    long coin = Integer.parseInt(str);
                    ResultSet red = SQLManager.stat.executeQuery("SELECT `coin` FROM `player` WHERE `id` = " + p.id + ";");
                    if (red != null && red.first()) {
                        int coinP = Integer.parseInt(red.getString("coin"));
                        int pre_gold = p.luong;
                        int pre_xu = p.c.xu;
                        int pre_yen = p.c.yen;
                        long pre_diamond = coinP;
                        if (coin <= coinP) {
                            coinP -= coin;
                            p.c.upxuMessage(coin * 1000);
                            SQLManager.stat.executeUpdate("UPDATE `player` SET `coin`=" + coinP + " WHERE `id`=" + p.id + " LIMIT 1;");
                            SQLManager.stat.executeUpdate("INSERT INTO transfer(`userid`,`cointruoc`,`coinsau`,`luongtruoc`,`luongsau`,`xutruoc`,`xusau`,`yentruoc`,`yensau`,`soluong`,`time`,`created_at`) VALUES (" + p.id + "," + pre_diamond + "," + coinP + "," + pre_gold + "," + p.luong + "," + pre_xu + "," + p.c.xu + "," + pre_yen + "," + p.c.yen + "," + coin + "," + (System.currentTimeMillis() / 1000L) + ",'" + Util.toDateString(Date.from(Instant.now())) + "');");

                        } else {
                            p.conn.sendMessageLog("Bạn không đủ coin để đổi ra Xu.");
                        }
                        p.flush();
                        red.close();
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            // đổi lượng
            case 11: { // đổi xu lượng
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 100) {
                        //thông báo
                        p.conn.sendMessageLog("bug cái lồn mẹ m");
                        return;
                    }
                    int luongdoi = Integer.parseInt(str);
                    if (luongdoi > p.luong) {
                        p.sendAddchatYellow("tuổi cặc");
                        return;
                    }
                    p.c.upxuMessage(luongdoi * 1);
                    p.upluongMessage(-luongdoi);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case 12: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 1000000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 1.000.000 1 Lần");
                        return;
                    }
                    int yendoi = Integer.parseInt(str);
                    if (yendoi > p.luong) {
                        p.sendAddchatYellow("Không đủ lượng");
                    } else {
                        p.c.upyenMessage(yendoi * 10000);
                        p.upluongMessage(-yendoi);
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case 13: { // Doi dhd
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 1000000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 1.000.000 1 Lần");
                        return;
                    }
                    int yendoi = Integer.parseInt(str);
                    if (yendoi < 0) {
                        p.lockAcc();
                    }
                    if (yendoi > p.c.pointUydanh) {
                        p.sendAddchatYellow("Không đủ điểm hoạt động");
                    } else {
                        p.c.upxuMessage(yendoi * 10000);
                        p.c.pointUydanh -= yendoi;
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            case 14: { // Doi dhd lấy lượng
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 1000000) {
                        //thông báo
                        p.conn.sendMessageLog("Tối Đa 1.000.000 1 Lần");
                        return;
                    }
                    int yendoi = Integer.parseInt(str);
                    if (yendoi > p.c.pointUydanh) {
                        p.sendAddchatYellow("Không đủ điểm hoạt động");
                    } else {
                        p.upluongMessage(yendoi * 10);
                        p.c.pointUydanh -= yendoi;
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }

            case 50: {
                try {
                    ClanManager.createClan(p, str);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không hợp lệ");
                }
                break;
            }
            // Tài Xỉu Lượng
            case 222: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 50000 || num > 100000) {
                        //thông báo
                        p.conn.sendMessageLog("Cược Tối Thiểu 50k Tối Đa 100k Lượng");
                        return;
                    }
                    int jointai = Integer.parseInt(str);
                    if (jointai % 10 != 0) {
                        p.conn.sendMessageLog("Số Tiền Cược Phải Chia Hết Cho 10.");
                        return;
                    }
                    Server.manager.taixiu[0].joinTai(p, jointai);
                } catch (NumberFormatException e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }
            case 223: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 50000 || num > 100000) {
                        //thông báo
                        p.conn.sendMessageLog("Cược Tối Thiểu 50k Tối Đa 100k Lượng");
                        return;
                    }
                    int joinxiu = Integer.parseInt(str);
                    if (joinxiu % 10 != 0) {
                        p.conn.sendMessageLog("Số Tiền Cược Phải Chia Hết Cho 10.");
                        return;
                    }
                    Server.manager.taixiu[0].joinXiu(p, joinxiu);
                } catch (NumberFormatException e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }
            // Chẵn Lẻ Xu
            case 224: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 1000000000) {
                        //thông báo
                        p.conn.sendMessageLog("Cược Tối Đa 1.000.000.000 Xu");
                        return;
                    }
                    int joinchan = Integer.parseInt(str);
                    if (joinchan % 10 != 0) {
                        p.conn.sendMessageLog("Số Tiền Cược Phải Chia Hết Cho 10.");
                        return;
                    }
                    Server.manager.chanle[0].joinChan(p, joinchan);
                } catch (NumberFormatException e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }
            case 225: {
                try {
                    int num = Integer.parseInt(str);
                    if (num < 1 || num > 1000000000) {
                        //thông báo
                        p.conn.sendMessageLog("Cược Tối Đa 1.000.000.000 Lượng");
                        return;
                    }
                    int joinle = Integer.parseInt(str);
                    if (joinle % 10 != 0) {
                        p.conn.sendMessageLog("Số Tiền Cược Phải Chia Hết Cho 10.");
                        return;
                    }
                    Server.manager.chanle[0].joinle(p, joinle);
                } catch (NumberFormatException e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }
            //Thay đổi exp
            case 9990: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (!Util.isNumeric(str) || str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    String check = str.replaceAll("\\s+", "");
                    check = str.replaceAll(" ", "").trim();
                    int expup = Integer.parseInt(check);
                    if (expup <= 0) {
                        expup = 1;
                    }
                    Manager.up_exp = expup;
                    p.sendAddchatYellow("Thay đổi tăng giá trị exp thành công");
                    break;

                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            //Thong bao
            case 9991: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    Manager.serverChat("Server", str);
                    p.sendAddchatYellow("Đăng thông báo thành công");
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            //kỹ năng
            case 9992: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (!Util.isNumeric(str) || str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    String check = str.replaceAll(" ", "").trim();
                    int kynang = Integer.parseInt(check);
                    p.c.spoint += kynang;
                    p.loadSkill();
                    if (kynang >= 0) {
                        p.sendAddchatYellow("Đã tăng thêm " + kynang + " điểm kỹ năng.");
                    } else {
                        p.sendAddchatYellow("Đã giảm đi " + kynang + " điểm kỹ năng.");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            //tiềm năng
            case 9993: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (!Util.isNumeric(str) || str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    String check = str.replaceAll(" ", "").trim();
                    int tiemnang = Integer.parseInt(check);
                    p.c.get().ppoint += tiemnang;
                    p.loadPpoint();
                    if (tiemnang >= 0) {
                        p.sendAddchatYellow("Đã tăng thêm " + tiemnang + " điểm tiềm năng.");
                    } else {
                        p.sendAddchatYellow("Đã giảm đi " + tiemnang + " điểm tiềm năng.");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            //tăng level
            case 9994: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (!Util.isNumeric(str) || str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    String check = str.replaceAll(" ", "").trim();
                    int levelup = Integer.parseInt(check);
                    int oldLv = p.c.get().level;
                    p.c.get().level = 1;
                    p.c.get().exp = 0;
                    p.c.get().expdown = 0;
                    p.updateExp(Level.getMaxExp(oldLv + levelup));
                    if (p.c.get().isHuman) {
                        p.c.setXPLoadSkill(p.c.get().exp);
                    } else {
                        p.c.clone.setXPLoadSkill(p.c.get().exp);
                    }
                    p.restPpoint();
                    p.restSpoint();
                    if (levelup >= 0) {
                        p.sendAddchatYellow("Đã tăng thêm " + levelup + " cấp độ.");
                    } else {
                        p.sendAddchatYellow("Đã giảm đi " + levelup + " cấp độ.");
                    }
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            //tăng lượng
            case 9995: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (!Util.isNumeric(str) || str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    String check = str.replaceAll(" ", "").trim();
                    int luongup = Integer.parseInt(check);
                    if (luongup >= 0) {
                        p.sendAddchatYellow("Đã tăng thêm " + Util.getFormatNumber(luongup) + " lượng.");
                    } else {
                        p.sendAddchatYellow("Đã giảm đi " + Util.getFormatNumber(luongup) + " lượng.");
                    }
                    p.upluongMessage(luongup);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            //tăng xu
            case 9996: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (!Util.isNumeric(str) || str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    String check = str.replaceAll(" ", "").trim();
                    int xuup = Integer.parseInt(str);
                    if (xuup >= 0) {
                        p.sendAddchatYellow("Đã tăng thêm " + Util.getFormatNumber(xuup) + " xu.");
                    } else {
                        p.sendAddchatYellow("Đã giảm đi " + Util.getFormatNumber(xuup) + " xu.");
                    }

                    p.c.upxuMessage(xuup);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            //tăng yên
            case 9997: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (!Util.isNumeric(str) || str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    String check = str.replaceAll(" ", "").trim();
                    int yenup = Integer.parseInt(check);
                    if (yenup >= 0) {
                        p.sendAddchatYellow("Đã tăng thêm " + Util.getFormatNumber(yenup) + " yên.");
                    } else {
                        p.sendAddchatYellow("Đã giảm đi " + Util.getFormatNumber(yenup) + " yên.");
                    }
                    p.c.upyenMessage(yenup);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }
            //bảo trì
            case 9998: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    if (!Util.isNumeric(str) || str.equals("")) {
                        p.conn.sendMessageLog("Giá trị nhập vào không hợp lệ");
                        return;
                    }
                    String check = str.replaceAll(" ", "").trim();
                    int minues = Integer.parseInt(check);
                    if (minues < 0 || minues > 10) {
                        p.conn.sendMessageLog("Giá trị nhập vào từ 0 -> 10 phút");
                        return;
                    }
                    p.sendAddchatYellow("Đã kích hoạt bảo trì Server sau " + minues + " phút.");
                    Thread t1 = new Thread(new Admin(minues, Server.gI()));
                    t1.start();
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            //khoá tài khoản
            case 9999: {
                try {
                    if (p.role != 10102003) {
                        p.lockAcc();
                        return;
                    }
                    Char temp = Client.gI().getNinja(str);
                    if (temp != null) {
                        Player banPlayer = Client.gI().getPlayer(temp.p.username);
                        if (banPlayer != null && banPlayer.role != 10102003) {
                            Client.gI().kickSession(banPlayer.conn);
                            try {
                                SQLManager.stat.executeUpdate("UPDATE `player` SET `ban`=1 WHERE `id`=" + banPlayer.id + " LIMIT 1;");
                            } catch (Exception e) {
                                e.printStackTrace();
                            }
                            p.conn.sendMessageLog("Đã khoá tài khoản: " + banPlayer.username + " - nhân vật: " + temp.name);
                        } else {
                            p.conn.sendMessageLog("Tài khoản này là ADMIN hoặc không tìm thấy tài khoản này!");
                        }
                    } else {
                        p.conn.sendMessageLog("Người chơi này không tồn tại hoặc không online!");
                    }
                    temp = null;
                } catch (Exception e) {
                    p.conn.sendMessageLog("Không Xác Định.");
                }
                break;
            }

            default: {
                break;
            }
        }

    }
}
