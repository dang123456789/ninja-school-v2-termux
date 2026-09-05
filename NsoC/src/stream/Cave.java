package stream;

import assembly.Char;
import assembly.Item;
import assembly.Map;
import server.Manager;

import java.util.ArrayList;
import java.util.HashMap;
import template.ItemTemplate;

public class Cave {

    public int caveID;
    public Map[] map;
    public long time;
    public int level = 0;
    public byte finsh = 0;
    public int x = -1;
    public ArrayList<Char> ninjas = new ArrayList();
    private static int idbase;
    private boolean rest = false;
    public static HashMap<Integer, Cave> caves = new HashMap();

    public Cave(int x) {
        this.x = x;
        this.caveID = idbase++;
        this.time = System.currentTimeMillis() + 3600000L;
        if (x == 1) { // Liên Hang
            this.map = new Map[24];
        }
        // sửa
        switch (x) {
            case 2:
                this.map = new Map[2];
                break;
            case 3:
                this.map = new Map[3];
                break;
            case 4:
                this.map = new Map[4];
                break;
            case 5:
                this.map = new Map[5];
                break;
            case 6:
                this.map = new Map[3];
                break;
            case 7:
                this.map = new Map[4];
                break;
            case 9:
                this.map = new Map[3];
                break;
            default:
                break;
        }

        this.initMap(x);

        for (byte i = 0; i < this.map.length; i++) {
            this.map[i].timeMap = this.time;
            this.map[i].start();
        }

        caves.put(this.caveID, this);
    }

    private void initMap(int x) {
        switch (x) {
            // Liên Hang
            case 1:
                this.map[0] = new Map(164, this, null, null, null, null, null, null);
                this.map[1] = new Map(165, this, null, null, null, null, null, null);
                this.map[2] = new Map(166, this, null, null, null, null, null, null);
                this.map[3] = new Map(167, this, null, null, null, null, null, null);
                this.map[4] = new Map(168, this, null, null, null, null, null, null);
                this.map[5] = new Map(169, this, null, null, null, null, null, null);
                this.map[6] = new Map(170, this, null, null, null, null, null, null);
                this.map[7] = new Map(171, this, null, null, null, null, null, null);
                this.map[8] = new Map(172, this, null, null, null, null, null, null);
                this.map[9] = new Map(173, this, null, null, null, null, null, null);
                this.map[10] = new Map(174, this, null, null, null, null, null, null);
                this.map[11] = new Map(175, this, null, null, null, null, null, null);
                this.map[12] = new Map(176, this, null, null, null, null, null, null);
                this.map[13] = new Map(177, this, null, null, null, null, null, null);
                this.map[14] = new Map(178, this, null, null, null, null, null, null);
                this.map[15] = new Map(179, this, null, null, null, null, null, null);
                this.map[16] = new Map(180, this, null, null, null, null, null, null);
                this.map[17] = new Map(181, this, null, null, null, null, null, null);
                this.map[18] = new Map(182, this, null, null, null, null, null, null);
                this.map[19] = new Map(183, this, null, null, null, null, null, null);
                this.map[20] = new Map(184, this, null, null, null, null, null, null);
                this.map[21] = new Map(185, this, null, null, null, null, null, null);
                this.map[22] = new Map(186, this, null, null, null, null, null, null);
                this.map[23] = new Map(187, this, null, null, null, null, null, null);
                break;
            //
            // sửa
            case 2:
                this.map[0] = new Map(162, this, null, null, null, null, null, null);
                this.map[1] = new Map(163, this, null, null, null, null, null, null);
                break;
            //
            case 3:
                this.map[0] = new Map(91, this, null, null, null, null, null, null);
                this.map[1] = new Map(92, this, null, null, null, null, null, null);
                this.map[2] = new Map(93, this, null, null, null, null, null, null);
                break;
            case 4:
                this.map[0] = new Map(94, this, null, null, null, null, null, null);
                this.map[1] = new Map(95, this, null, null, null, null, null, null);
                this.map[2] = new Map(96, this, null, null, null, null, null, null);
                this.map[3] = new Map(97, this, null, null, null, null, null, null);
                break;
            case 5:
                this.map[0] = new Map(105, this, null, null, null, null, null, null);
                this.map[1] = new Map(106, this, null, null, null, null, null, null);
                this.map[2] = new Map(107, this, null, null, null, null, null, null);
                this.map[3] = new Map(108, this, null, null, null, null, null, null);
                this.map[4] = new Map(109, this, null, null, null, null, null, null);
                break;
            case 6:
                this.map[0] = new Map(114, this, null, null, null, null, null, null);
                this.map[1] = new Map(115, this, null, null, null, null, null, null);
                this.map[2] = new Map(116, this, null, null, null, null, null, null);
                break;
            case 7:
                this.map[0] = new Map(125, this, null, null, null, null, null, null);
                this.map[1] = new Map(126, this, null, null, null, null, null, null);
                this.map[2] = new Map(127, this, null, null, null, null, null, null);
                this.map[3] = new Map(128, this, null, null, null, null, null, null);
                break;
            case 9:
                this.map[0] = new Map(157, this, null, null, null, null, null, null);
                this.map[1] = new Map(158, this, null, null, null, null, null, null);
                this.map[2] = new Map(159, this, null, null, null, null, null, null);
                break;
        }

    }

    public void updateXP(long xp) {
        synchronized (this) {
            for (short i = 0; i < this.ninjas.size(); i++) {
                if (this.ninjas.get(i) != null) {
                    (this.ninjas.get(i)).p.updateExp(xp);
                }
            }

        }
    }

    public void updatePoint(int point) {
        synchronized (this) {
            for (short i = 0; i < this.ninjas.size(); i++) {
                if (this.ninjas.get(i) != null) {
                    this.ninjas.get(i).pointCave += point;
                    ((Char) this.ninjas.get(i)).p.setPointPB(((Char) this.ninjas.get(i)).pointCave);
                }
            }

        }
    }

    public void rest() {
        if (!this.rest) {
            this.rest = true;
            try {
                synchronized (this) {
                    Map ma;
                    Char _char;
                    while (this.ninjas.size() > 0) {
                        _char = this.ninjas.remove(0);
                        if (_char != null) {
                            if (_char.party != null && _char.party.cave != null && _char.party.cave.caveID == this.caveID) {
                                _char.party.cave = null;
                            }
                            _char.caveID = -1;
                            _char.tileMap.leave(_char.p);
                            _char.p.restCave();
                            ma = Manager.getMapid(_char.mapKanata);
                            byte k;
                            for (k = 0; k < ma.area.length; k++) {
                                if (ma.area[k].numplayers < ma.template.maxplayers) {
                                    ma.area[k].EnterMap0(_char);
                                    break;
                                }
                            }
                        }
                    }
                }
                byte i;
                for (i = 0; i < this.map.length; i++) {
                    this.map[i].close();
                    this.map[i] = null;
                }
                synchronized (Cave.caves) {
                    if (Cave.caves.containsKey(this.caveID)) {
                        Cave.caves.remove(this.caveID);
                    }
                }
            } catch (Exception e) {
                byte i;
                for (i = 0; i < this.map.length; i++) {
                    if (this.map[i] != null) {
                        this.map[i].close();
                        this.map[i] = null;
                    }

                }
                synchronized (Cave.caves) {
                    if (Cave.caves.containsKey(this.caveID)) {
                        Cave.caves.remove(this.caveID);
                    }
                }
            }
        }
    }

    public void finish() {
        synchronized (this) {
            this.level++;
            byte i;
            if (this.x != 6) {
                this.time = System.currentTimeMillis() + 20000L;
                for (i = 0; i < this.map.length; i++) {
                    this.map[i].timeMap = this.time;
                }
            }

            if (this.x != 6 || this.finsh == 0) {
                this.finsh++;
                Char _char;
                for (i = 0; i < this.ninjas.size(); i++) {
                    _char = this.ninjas.get(i);
                    if (_char != null && _char.p != null && _char.p.conn != null) {
                        _char.p.setTimeMap((int) (this.time - System.currentTimeMillis()) / 1000);
                        _char.p.sendAddchatYellow("Hoàn thành hang động");
                        if (_char.party != null && _char.party.cave != null) {
                            _char.party.cave = null;
                        }
                        if (!_char.clan.clanName.isEmpty()) {
                            _char.p.upExpClan(10);
                        }
                    }
                }
            }
        }
    }

    public void openMap() {
        synchronized (this) {
            this.level++;
            if (this.level < this.map.length) {
                for (byte i = 0; i < this.ninjas.size(); ++i) {
                    if (this.ninjas.get(i) != null) {
                        this.ninjas.get(i).p.sendAddchatYellow(this.map[this.level].template.name + " đã được mở");
                    }
                }
            }

        }
    }
}
